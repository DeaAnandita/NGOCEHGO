<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataSejahteraKeluarga;
use App\Models\DataKeluarga;
use App\Models\MasterPembangunanKeluarga;

class DataSejahteraKeluargaPdfExport
{
    public static function export()
    {
        // Ambil semua data dengan relasi keluarga & master pembangunan keluarga
        $dataSejahtera = DataSejahteraKeluarga::with(['keluarga', 'masterPembangunan'])
            ->orderBy('no_kk', 'asc')
            ->get();

        if ($dataSejahtera->isEmpty()) {
            abort(404, 'Tidak ada data Sejahtera Keluarga untuk diekspor.');
        }

        // Buat PDF dari view
        $pdf = Pdf::loadView('exports.sejahterakeluarga_pdf', [
            'dataSejahtera' => $dataSejahtera,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('data_sejahtera_keluarga_' . date('Ymd_His') . '.pdf');
    }
}
