<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataAsetKeluarga;
use App\Models\MasterAsetKeluarga;
use App\Models\MasterJawab;

class DataAsetKeluargaExport
{
    public static function export()
    {
        $filename = 'data_aset_keluarga_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Ambil master aset dan jawaban
        $asetList = MasterAsetKeluarga::orderBy('kdasetkeluarga')->pluck('asetkeluarga', 'kdasetkeluarga')->toArray();
        $jawabanList = MasterJawab::pluck('jawab', 'kdjawab')->toArray();

        // Buat header dinamis
        $header = array_merge(['No KK'], array_values($asetList));
        $writer->addHeader($header);

        // Ambil semua data keluarga
        $dataKeluarga = DataAsetKeluarga::all();

        foreach ($dataKeluarga as $keluarga) {
            $row = ['No KK' => $keluarga->no_kk];

            // Loop tiap aset (1-42)
            foreach ($asetList as $kode => $nama) {
                $kolom = "asetkeluarga_{$kode}";
                $idJawab = $keluarga->$kolom ?? 0;
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}
