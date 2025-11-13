<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataBangunKeluarga; 
use Illuminate\Support\Facades\View;

class DataBangunKeluargaPdfExport
{
    public static function export($periode = null)
    {
        // Ambil semua data bangun keluarga
        $data = DataBangunKeluarga::with(['keluarga', 'desa'])->get();

        // Hitung statistik sederhana (opsional untuk laporan)
        $total = $data->count();
        $layak = $data->where('kategori_kelayakan', 'Layak')->count();
        $tidakLayak = $data->where('kategori_kelayakan', 'Tidak Layak')->count();

        $persenLayak = $total > 0 ? round(($layak / $total) * 100, 1) : 0;
        $persenTidakLayak = $total > 0 ? round(($tidakLayak / $total) * 100, 1) : 0;

        // Kirim ke view untuk PDF
        $pdf = Pdf::loadView('exports.bangunkeluarga_pdf', [
            'periode' => $periode ?? now()->translatedFormat('F Y'),
            'data' => $data,
            'total' => $total,
            'layak' => $layak,
            'tidakLayak' => $tidakLayak,
            'persenLayak' => $persenLayak,
            'persenTidakLayak' => $persenTidakLayak,
            'tanggal' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan_bangun_keluarga_' . date('Ymd_His') . '.pdf');
    }
}
