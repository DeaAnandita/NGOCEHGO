<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataAsetTernak;
use App\Models\MasterAsetTernak;

class DataAsetTernakExport
{
    public static function export()
    {
        // Nama file hasil export
        $filename = 'data_aset_ternak_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        // Buat writer Excel
        $writer = SimpleExcelWriter::create($path);

        // Ambil daftar aset ternak (misal: sapi, kambing, ayam, dll)
        $asetList = MasterAsetTernak::orderBy('kdasetternak')
            ->pluck('asetternak', 'kdasetternak')
            ->toArray();

        // Buat header dinamis (No KK + nama aset)
        $header = array_merge(['No KK'], array_values($asetList));
        $writer->addHeader($header);

        // Ambil semua data ternak
        $dataTernak = DataAsetTernak::all();

        // Loop tiap data keluarga
        foreach ($dataTernak as $ternak) {
            $row = ['No KK' => $ternak->no_kk];

            // Loop tiap aset ternak
            foreach ($asetList as $kode => $nama) {
                // Nama kolom di tabel data_aset_ternak (contoh: asetternak_1, asetternak_2, dst)
                $kolom = "asetternak_{$kode}";

                // Ambil nilai kolom (bisa jumlah, status, atau teks)
                $nilai = $ternak->$kolom ?? 'TIDAK DIISI';

                $row[$nama] = $nilai;
            }

            $writer->addRow($row);
        }

        $writer->close();

        // Kembalikan file untuk diunduh
        return response()->download($path);
    }
}
