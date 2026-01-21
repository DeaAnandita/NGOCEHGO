<?php

namespace App\Http\Controllers\AdminUmum;

use App\Exports\BukuKeputusanExport;
use App\Http\Controllers\Controller;
use App\Models\BukuKeputusan;
use App\Models\MasterJenisKeputusanUmum;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use setasign\Fpdi\Fpdi;

class BukuKeputusanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search  = $request->get('search');

        $query = BukuKeputusan::with('jenisKeputusan');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_keputusan', 'like', "%{$search}%")
                    ->orWhere('judul_keputusan', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('tanggal_keputusan', 'desc')
            ->paginate($perPage);

        return view('admin_umum.keputusan.index', compact('data', 'perPage', 'search'));
    }

    public function create()
    {
        $jenis = MasterJenisKeputusanUmum::orderBy('jeniskeputusan_umum')->get();
        return view('admin_umum.keputusan.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_keputusan' => 'required|unique:buku_keputusan,kd_keputusan',
            'nomor_keputusan' => 'required',
            'tanggal_keputusan' => 'required|date',
            'judul_keputusan' => 'required',
            'kdjeniskeputusan_umum' => 'required',
            'file_keputusan' => 'nullable|mimes:pdf|max:2048'
        ]);

        $filePath = null;
        if ($request->hasFile('file_keputusan')) {
            $filePath = $request->file('file_keputusan')
                ->store('keputusan', 'public');
        }

        BukuKeputusan::create([
            'kd_keputusan' => $request->kd_keputusan,
            'nomor_keputusan' => $request->nomor_keputusan,
            'tanggal_keputusan' => $request->tanggal_keputusan,
            'judul_keputusan' => $request->judul_keputusan,
            'kdjeniskeputusan_umum' => $request->kdjeniskeputusan_umum,
            'uraian_keputusan' => $request->uraian_keputusan,
            'keterangan_keputusan' => $request->keterangan_keputusan,
            'file_keputusan' => $filePath,
            'userinput' => Auth::user()->name ?? 'system',
            'inputtime' => now(),
        ]);

        return redirect()
            ->route('admin-umum.keputusan.index')
            ->with('success', 'Data keputusan berhasil disimpan');
    }

    public function show($id)
    {
        $data = BukuKeputusan::with('jenisKeputusan')->findOrFail($id);
        return view('admin_umum.keputusan.show', compact('data'));
    }

    public function edit($id)
    {
        $data  = BukuKeputusan::findOrFail($id);
        $jenis = MasterJenisKeputusanUmum::orderBy('jeniskeputusan_umum')->get();

        return view('admin_umum.keputusan.edit', compact('data', 'jenis'));
    }

    public function update(Request $request, $id)
    {
        $data = BukuKeputusan::findOrFail($id);

        $request->validate([
            'nomor_keputusan' => 'required',
            'tanggal_keputusan' => 'required|date',
            'judul_keputusan' => 'required',
            'kdjeniskeputusan_umum' => 'required',
            'file_keputusan' => 'nullable|mimes:pdf|max:2048'
        ]);

        $filePath = $data->file_keputusan;

        if ($request->hasFile('file_keputusan')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $filePath = $request->file('file_keputusan')
                ->store('keputusan', 'public');
        }

        $data->update([
            'nomor_keputusan' => $request->nomor_keputusan,
            'tanggal_keputusan' => $request->tanggal_keputusan,
            'judul_keputusan' => $request->judul_keputusan,
            'kdjeniskeputusan_umum' => $request->kdjeniskeputusan_umum,
            'uraian_keputusan' => $request->uraian_keputusan,
            'keterangan_keputusan' => $request->keterangan_keputusan,
            'file_keputusan' => $filePath,
        ]);

        return redirect()
            ->route('admin-umum.keputusan.index')
            ->with('success', 'Data keputusan berhasil diperbarui');
    }

    public function destroy($kd_keputusan)
    {
        $data = BukuKeputusan::findOrFail($kd_keputusan);

        if ($data->file_keputusan && Storage::disk('public')->exists($data->file_keputusan)) {
            Storage::disk('public')->delete($data->file_keputusan);
        }

        $data->delete();

        return back()->with('success', 'Data keputusan berhasil dihapus');
    }
    public function export()
    {
        $data = BukuKeputusan::with('jenisKeputusan')
            ->orderBy('tanggal_keputusan', 'desc')
            ->get();

        return Excel::download(
            new BukuKeputusanExport($data),
            'buku-keputusan.xlsx'
        );
    }

    public function exportPdf()
    {
        $data = BukuKeputusan::with('jenisKeputusan')
            ->orderBy('tanggal_keputusan', 'desc')
            ->get();

        // ===========================
        // 1. Buat PDF REKAP
        // ===========================
        $pdfRekap = Pdf::loadView('admin_umum.keputusan.rekap', [
            'data' => $data
        ])->setPaper('A4', 'landscape')->output();

        $rekapPath = storage_path('app/tmp/rekap_keputusan.pdf');
        if (!file_exists(dirname($rekapPath))) {
            mkdir(dirname($rekapPath), 0777, true);
        }
        file_put_contents($rekapPath, $pdfRekap);

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

        // Masukkan semua file keputusan
        foreach ($data as $d) {

            if (!$d->file_keputusan) continue;

            $file = storage_path('app/public/' . $d->file_keputusan);
            if (!file_exists($file)) continue;

            $pageCount = 0;
            $tmp = null;

            try {
                $pageCount = $pdf->setSourceFile($file);
            } catch (\Throwable $e) {
                $pageCount = 0;
            }

            // Kalau PDF modern → convert dulu
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

        // ===========================
        // 3. Kirim ke browser
        // ===========================
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="buku-keputusan-final.pdf"');
    }
    private function normalizePdf($input, $output)
    {
        $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH '
            . '-sOutputFile="' . $output . '" "' . $input . '"';

        exec($cmd);
    }
}
