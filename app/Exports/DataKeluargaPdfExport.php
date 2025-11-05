<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataKeluarga;
use App\Models\MasterDusun;
use App\Models\MasterMutasimasuk;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;

class DataKeluargaPDFExport
{
    public static function export()
    {
        $dataKeluarga = DataKeluarga::all();

        // Kirim ke view
        $pdf = Pdf::loadView('exports.keluarga_pdf', [
            'dataKeluarga' => $dataKeluarga,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('data_keluarga_' . date('Ymd_His') . '.pdf');
    }
}
    