<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataKonflikSosial;
use App\Models\MasterKonflikSosial;
use Illuminate\Support\Facades\View;

class DataKonflikSosialPdfExport
{
    public static function export()
    {
        $masterKonflik = MasterKonflikSosial::orderBy('kdkonfliksosial', 'asc')->get();
        $dataKonflik = DataKonflikSosial::with('keluarga')->get();

        $jawabanMap = [0 => 'Tidak Diisi', 1 => 'Ada', 2 => 'Tidak Ada'];

        // Kirim ke Blade untuk tampilan tabel
        $pdf = Pdf::loadView('laporan.data_konfliksosial', [
            'dataKonflik' => $dataKonflik,
            'masterKonflik' => $masterKonflik,
            'jawabanMap' => $jawabanMap,
            'tanggal' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Data_Konflik_Sosial.pdf');
    }
}
