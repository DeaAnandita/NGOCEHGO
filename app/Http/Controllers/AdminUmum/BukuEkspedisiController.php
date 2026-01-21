<?php

namespace App\Http\Controllers\AdminUmum;

use App\Http\Controllers\Controller;
use App\Models\BukuEkspedisi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use App\Exports\BukuEkspedisiExport;
use Maatwebsite\Excel\Facades\Excel;

class BukuEkspedisiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data = BukuEkspedisi::when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('ekspedisi_nomorsurat', 'like', "%{$search}%")
                    ->orWhere('ekspedisi_identitassurat', 'like', "%{$search}%");
            });
        })
            ->orderBy('ekspedisi_tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin_umum.ekspedisi.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('admin_umum.ekspedisi.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'kdekspedisi' => 'required|unique:buku_ekspedisi,kdekspedisi',
            'ekspedisi_tanggal' => 'required|date',
            'ekspedisi_identitassurat' => 'required',
            'ekspedisi_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->only([
            'kdekspedisi',
            'ekspedisi_tanggal',
            'ekspedisi_tanggalsurat',
            'ekspedisi_nomorsurat',
            'ekspedisi_identitassurat',
            'ekspedisi_isisurat',
            'ekspedisi_keterangan',
        ]);

        // 🔥 simpan file dengan Laravel Storage
        if ($request->hasFile('ekspedisi_file')) {
            $data['ekspedisi_file'] = $request->file('ekspedisi_file')
                ->store('ekspedisi', 'public');
        }

        $data['userinput'] = Auth::user()->name ?? 'system';
        $data['inputtime'] = now();

        BukuEkspedisi::create($data);

        return redirect()
            ->route('admin-umum.ekspedisi.index')
            ->with('success', 'Data ekspedisi berhasil disimpan');
    }


    public function show($id)
    {
        $data = BukuEkspedisi::where('kdekspedisi', $id)->firstOrFail();
        return view('admin_umum.ekspedisi.show', compact('data'));
    }

    public function edit($id)
    {
        $data = BukuEkspedisi::where('kdekspedisi', $id)->firstOrFail();
        return view('admin_umum.ekspedisi.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = BukuEkspedisi::where('kdekspedisi', $id)->firstOrFail();

        $request->validate([
            'ekspedisi_tanggal' => 'required|date',
            'ekspedisi_identitassurat' => 'required',
            'ekspedisi_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $updateData = $request->only([
            'ekspedisi_tanggal',
            'ekspedisi_tanggalsurat',
            'ekspedisi_nomorsurat',
            'ekspedisi_identitassurat',
            'ekspedisi_isisurat',
            'ekspedisi_keterangan',
        ]);

        // 🔥 upload baru → hapus yang lama
        if ($request->hasFile('ekspedisi_file')) {

            if ($data->ekspedisi_file && Storage::disk('public')->exists($data->ekspedisi_file)) {
                Storage::disk('public')->delete($data->ekspedisi_file);
            }

            $updateData['ekspedisi_file'] = $request->file('ekspedisi_file')
                ->store('ekspedisi', 'public');
        }

        $data->update($updateData);

        return redirect()
            ->route('admin-umum.ekspedisi.index')
            ->with('success', 'Data ekspedisi berhasil diperbarui');
    }


    public function destroy($id)
    {
        BukuEkspedisi::where('kdekspedisi', $id)->delete();

        return redirect()
            ->route('admin-umum.ekspedisi.index')
            ->with('success', 'Data ekspedisi berhasil dihapus');
    }
    public function exportPdf(Request $request)
    {
        $query = BukuEkspedisi::query();

        if ($request->search) {
            $query->where('ekspedisi_nomorsurat', 'like', "%{$request->search}%")
                ->orWhere('ekspedisi_identitassurat', 'like', "%{$request->search}%");
        }

        $data = $query->orderBy('ekspedisi_tanggal')->get();

        // ===========================
        // 1. Buat PDF Rekap
        // ===========================
        $rekapPdf = Pdf::loadView('admin_umum.ekspedisi.export_pdf', [
            'data'   => $data,
            'search' => $request->search
        ])->setPaper('A4', 'landscape')->output();

        $tmpDir = storage_path('app/tmp');
        if (!file_exists($tmpDir)) mkdir($tmpDir, 0777, true);

        $rekapPath = $tmpDir . '/rekap_ekspedisi.pdf';
        file_put_contents($rekapPath, $rekapPdf);

        // ===========================
        // 2. Gabungkan semua PDF
        // ===========================
        $pdf = new Fpdi();

        // Masukkan Rekap
        $rekapPages = $pdf->setSourceFile($rekapPath);
        for ($i = 1; $i <= $rekapPages; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }

        // ===========================
        // 3. Masukkan semua file surat
        // ===========================
        foreach ($data as $d) {

            if (!$d->ekspedisi_file) continue;

            $file = storage_path('app/public/' . $d->ekspedisi_file);
            if (!file_exists($file)) continue;

            $pageCount = 0;
            $tmp = null;

            // Coba baca langsung
            try {
                $pageCount = $pdf->setSourceFile($file);
            } catch (\Throwable $e) {
                $pageCount = 0;
            }

            // Kalau gagal → convert PDF modern
            if ($pageCount < 1) {
                $tmp = storage_path('app/tmp/' . uniqid() . '.pdf');
                $this->normalizePdf($file, $tmp);

                if (file_exists($tmp)) {
                    try {
                        $pageCount = $pdf->setSourceFile($tmp);
                        $file = $tmp;
                    } catch (\Throwable $e) {
                        $pageCount = 0;
                    }
                }
            }

            if ($pageCount < 1) {
                if ($tmp && file_exists($tmp)) unlink($tmp);
                continue;
            }

            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }

            if ($tmp && file_exists($tmp)) unlink($tmp);
        }

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="buku_ekspedisi.pdf"');
    }
    private function normalizePdf($input, $output)
    {
        $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH '
            . '-sOutputFile="' . $output . '" "' . $input . '"';

        exec($cmd);
    }
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new BukuEkspedisiExport($request->search),
            'buku_ekspedisi.xlsx'
        );
    }
}
