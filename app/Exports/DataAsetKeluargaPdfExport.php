<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataAsetKeluarga;
use App\Models\MasterAsetKeluarga;
use App\Models\MasterJawab;
use Illuminate\Support\Facades\View;

class DataAsetKeluargaPDFExport
{
    public static function export()
    {
        $dataKeluarga = DataAsetKeluarga::with('keluarga')->get();
        $asetList = MasterAsetKeluarga::orderBy('kdasetkeluarga')->pluck('asetkeluarga', 'kdasetkeluarga')->toArray();
        $jawabanList = MasterJawab::pluck('jawab', 'kdjawab')->toArray();

        // Bagi aset jadi 3 bagian (1-14, 15-28, 29-42)
        $chunks = array_chunk($asetList, 14, true);

        $pdf = Pdf::loadView('exports.asetkeluarga_pdf', [
            'dataKeluarga' => $dataKeluarga,
            'chunks' => $chunks,
            'jawabanList' => $jawabanList
        ])->setPaper('a4', 'landscape');

        return $pdf->download('data_aset_keluarga_' . date('Ymd_His') . '.pdf');
    }
}
