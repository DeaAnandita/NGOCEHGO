<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataAsetTernak;
use App\Models\MasterAsetTernak;
use Carbon\Carbon;

class DataAsetTernakPdfExport
{
    /**
     * Export semua data aset ternak ke PDF
     */
    public static function export()
    {
        // 🔹 Ambil semua data aset ternak beserta relasi keluarga
        $data = DataAsetTernak::with('keluarga')->get();

        // 🔹 Ambil data master jenis ternak
        $master = MasterAsetTernak::orderBy('kdasetternak')->get();

        // 🔹 Hitung total keluarga & total unit ternak
        $totalKeluarga = $data->count();
        $totalUnit = 0;
        foreach ($data as $row) {
            for ($i = 1; $i <= 24; $i++) {
                $totalUnit += (int) ($row->{"asetternak_$i"} ?? 0);
            }
        }

        // 🔹 Kirim data ke view exports/asetternak_pdf.blade.php
        $pdf = Pdf::loadView('exports.asetternak_pdf', [
            'data' => $data,
            'master' => $master,
            'totalKeluarga' => $totalKeluarga,
            'totalUnit' => $totalUnit,
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        // 🔹 Nama file otomatis
        $filename = 'data_asetternak_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
