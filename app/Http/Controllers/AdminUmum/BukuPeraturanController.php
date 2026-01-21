<?php

namespace App\Http\Controllers\AdminUmum;

use App\Exports\BukuPeraturanExport;
use App\Http\Controllers\Controller;
use App\Models\BukuPeraturan;
use App\Models\MasterJenisPeraturanDesa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use setasign\Fpdi\Fpdi;

class BukuPeraturanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $data = BukuPeraturan::with('jenisPeraturanDesa')
            ->when($request->search, function ($q) use ($request) {
                $q->where('nomorperaturan', 'like', '%' . $request->search . '%')
                    ->orWhere('judulpengaturan', 'like', '%' . $request->search . '%');
            })
            ->orderByDesc('inputtime')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin_umum.peraturan.index', compact('data'));
    }

    public function create()
    {
        $jenis = MasterJenisPeraturanDesa::orderBy('jenisperaturandesa')->get();
        return view('admin_umum.peraturan.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdperaturan' => 'required|unique:buku_peraturans,kdperaturan',
            'kdjenisperaturandesa' => 'required',
            'nomorperaturan' => 'required',
            'judulpengaturan' => 'required',
            'filepengaturan' => 'nullable|mimes:pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('filepengaturan')) {
            $filePath = $request->file('filepengaturan')
                ->store('peraturan', 'public');
        }

        BukuPeraturan::create([
            'kdperaturan' => $request->kdperaturan,
            'kdjenisperaturandesa' => $request->kdjenisperaturandesa,
            'nomorperaturan' => $request->nomorperaturan,
            'judulpengaturan' => $request->judulpengaturan,
            'uraianperaturan' => $request->uraianperaturan,
            'kesepakatanperaturan' => $request->kesepakatanperaturan,
            'keteranganperaturan' => $request->keteranganperaturan,
            'filepengaturan' => $filePath,
            'userinput' => Auth::user()->name ?? 'system',
            'inputtime' => now(),
        ]);

        return redirect()
            ->route('admin-umum.peraturan.index')
            ->with('success', 'Data peraturan berhasil disimpan');
    }

    public function show($id)
    {
        $data = BukuPeraturan::with('jenisPeraturanDesa')->findOrFail($id);
        return view('admin_umum.peraturan.show', compact('data'));
    }

    public function edit($id)
    {
        $data  = BukuPeraturan::findOrFail($id);
        $jenis = MasterJenisPeraturanDesa::orderBy('jenisperaturandesa')->get();

        return view('admin_umum.peraturan.edit', compact('data', 'jenis'));
    }

    public function update(Request $request, $id)
    {
        $data = BukuPeraturan::findOrFail($id);

        $request->validate([
            'kdjenisperaturandesa' => 'required',
            'nomorperaturan' => 'required',
            'judulpengaturan' => 'required',
            'filepengaturan' => 'nullable|mimes:pdf|max:2048',
        ]);

        $filePath = $data->filepengaturan;

        if ($request->hasFile('filepengaturan')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $filePath = $request->file('filepengaturan')
                ->store('peraturan', 'public');
        }

        $data->update([
            'kdjenisperaturandesa' => $request->kdjenisperaturandesa,
            'nomorperaturan' => $request->nomorperaturan,
            'judulpengaturan' => $request->judulpengaturan,
            'uraianperaturan' => $request->uraianperaturan,
            'kesepakatanperaturan' => $request->kesepakatanperaturan,
            'keteranganperaturan' => $request->keteranganperaturan,
            'filepengaturan' => $filePath,
        ]);

        return redirect()
            ->route('admin-umum.peraturan.index')
            ->with('success', 'Data peraturan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $data = BukuPeraturan::findOrFail($id);

        if ($data->filepengaturan && Storage::disk('public')->exists($data->filepengaturan)) {
            Storage::disk('public')->delete($data->filepengaturan);
        }

        $data->delete();

        return back()->with('success', 'Data peraturan berhasil dihapus');
    }
    public function export()
    {
        $data = BukuPeraturan::with('jenisPeraturanDesa')
            ->orderBy('inputtime', 'desc')
            ->get();

        return Excel::download(
            new BukuPeraturanExport($data),
            'buku-peraturan.xlsx'
        );
    }

    public function exportPdf()
    {
        $data = BukuPeraturan::with('jenisPeraturanDesa')
            ->orderBy('inputtime', 'desc')
            ->get();

        // ===========================
        // 1. Buat PDF REKAP
        // ===========================
        $pdfRekap = Pdf::loadView('admin_umum.peraturan.rekap', [
            'data' => $data
        ])->setPaper('A4', 'landscape')->output();

        $rekapPath = storage_path('app/tmp/rekap_peraturan.pdf');
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

        // Masukkan semua file peraturan
        foreach ($data as $d) {

            if (!$d->filepengaturan) continue;

            $file = storage_path('app/public/' . $d->filepengaturan);
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
            ->header('Content-Disposition', 'attachment; filename="buku-peraturan-final.pdf"');
    }
    private function normalizePdf($input, $output)
{
    $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH '
        . '-sOutputFile="' . $output . '" "' . $input . '"';

    exec($cmd);
}
}
