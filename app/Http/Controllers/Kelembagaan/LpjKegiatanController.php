<?php

namespace App\Http\Controllers\Kelembagaan;

use App\Exports\LpjExport;
use App\Http\Controllers\Controller;
use App\Models\kegiatan;
use App\Models\LpjKegiatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;

class LpjKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = LPJKegiatan::with('kegiatan');

        if ($request->search) {
            $query->whereHas('kegiatan', function ($q) use ($request) {
                $q->where('nama_kegiatan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->paginate(10);

        return view('kelembagaan.lpj.index', compact('data'));
    }


    public function create()
    {
        $kegiatan = Kegiatan::with('pencairanDana.realisasi')->get();
        return view('kelembagaan.lpj.create', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'file_lpj' => 'nullable|mimes:pdf|max:5120'
        ]);

        $kegiatan = Kegiatan::with('pencairanDana.realisasi')->findOrFail($request->kegiatan_id);

        $totalAnggaran = $kegiatan->pagu_anggaran;
        $totalRealisasi = $kegiatan->pencairanDana->flatMap->realisasi->sum('jumlah');
        $sisa = $totalAnggaran - $totalRealisasi;

        $data = [
            'kegiatan_id' => $kegiatan->id,
            'total_anggaran' => $totalAnggaran,
            'total_realisasi' => $totalRealisasi,
            'sisa_anggaran' => $sisa,
            'status' => 1, // diajukan
        ];

        if ($request->hasFile('file_lpj')) {
            $data['file_lpj'] = $request->file('file_lpj')->store('lpj', 'public');
        }

        LpjKegiatan::create($data);

        return redirect()->route('kelembagaan.lpj.index')
            ->with('success', 'LPJ berhasil diajukan');
    }

    public function show($id)
    {
        $lpj = LpjKegiatan::with('kegiatan.pencairanDana.realisasi')->findOrFail($id);
        return view('kelembagaan.lpj.show', compact('lpj'));
    }

    public function edit($id)
    {
        $lpj = LpjKegiatan::findOrFail($id);

        // USER hanya boleh edit jika status = diajukan / ditolak
        if (Auth::user()->role->slug === 'user' && !in_array($lpj->status, [1, 3])) {
            abort(403, 'LPJ sudah diproses');
        }

        return view('kelembagaan.lpj.edit', compact('lpj'));
    }

    public function update(Request $request, $id)
    {
        $lpj = LpjKegiatan::findOrFail($id);
        $role = Auth::user()->role->slug;

        // 🔒 User tidak boleh ubah LPJ yang sudah disetujui
        if ($role === 'user' && !in_array($lpj->status, [1, 3])) {
            abort(403, 'LPJ sudah dikunci');
        }

        /**
         * =========================
         * APPROVE / REJECT
         * =========================
         */
        if ($request->has('status')) {

            if (!in_array($role, ['super_admin', 'dev'])) {
                abort(403, 'Tidak boleh mengubah status');
            }

            $request->validate([
                'status' => 'required|in:2,3'
            ]);

            $lpj->update([
                'status' => $request->status
            ]);

            return back()->with('success', 'Status LPJ berhasil diperbarui');
        }

        /**
         * =========================
         * EDIT DATA LPJ
         * =========================
         */
        $request->validate([
            'file_lpj' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'catatan' => 'nullable|string'
        ]);

        $data = [];

        if ($request->hasFile('file_lpj')) {
            $data['file_lpj'] = $request->file('file_lpj')->store('lpj', 'public');
        }

        if ($request->filled('catatan')) {
            $data['catatan'] = $request->catatan;
        }

        $lpj->update($data);

        return redirect()
            ->route('kelembagaan.lpj.index')
            ->with('success', 'LPJ berhasil diperbarui');
    }



    public function destroy($id)
    {
        $lpj = LpjKegiatan::findOrFail($id);

        if ($lpj->file_lpj) {
            Storage::disk('public')->delete($lpj->file_lpj);
        }

        $lpj->delete();

        return back()->with('success', 'LPJ dihapus');
    }
    public function export()
    {
        $data = LPJKegiatan::with('kegiatan')->get();
        return Excel::download(new LpjExport($data), 'lpj.xlsx');
    }


    public function exportPdf()
    {
        $data = LPJKegiatan::with('kegiatan')->get();

        // ===========================
        // 1. Buat PDF REKAP
        // ===========================
        $pdfRekap = Pdf::loadView('kelembagaan.lpj.rekap', [
            'data' => $data
        ])->setPaper('A4', 'landscape')->output();

        $rekapPath = storage_path('app/tmp/rekap.pdf');
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

        // Masukkan semua file LPJ
        foreach ($data as $d) {

            if (!$d->file_lpj) continue;

            $file = storage_path('app/public/' . $d->file_lpj);
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

            // Kalau tetap gagal → skip file ini
            if ($pageCount < 1) {
                if ($tmp && file_exists($tmp)) unlink($tmp);
                continue;
            }

            // Import semua halaman PDF LPJ ini
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
            ->header('Content-Disposition', 'attachment; filename="lpj_final.pdf"');
    }

    private function normalizePdf($input, $output)
    {
        $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH '
            . '-sOutputFile="' . $output . '" "' . $input . '"';

        exec($cmd);
    }
}
