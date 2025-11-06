<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataAsetPerikanan;
use App\Models\MasterAsetPerikanan;

class DataAsetPerikananExport
{
    public static function export()
    {
        // Nama file export
        $filename = 'data_aset_perikanan_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        // Buat writer Excel
        $writer = SimpleExcelWriter::create($path);

        // Ambil daftar master aset perikanan (misalnya: kolam, jaring, aerator, dll)
        $asetList = MasterAsetPerikanan::orderBy('kdasetperikanan')
            ->pluck('asetperikanan', 'kdasetperikanan')
            ->toArray();

        // Buat header kolom (No KK + daftar aset)
        $header = array_merge(['No KK'], array_values($asetList));
        $writer->addHeader($header);

        // Ambil seluruh data perikanan
        $dataPerikanan = DataAsetPerikanan::all();

        // Isi data per baris
        foreach ($dataPerikanan as $perikanan) {
            $row = ['No KK' => $perikanan->no_kk];

            foreach ($asetList as $kode => $nama) {
                // Nama kolom di database: contoh asetperikanan_1, asetperikanan_2, dst
                $kolom = "asetperikanan_{$kode}";
                $nilai = $perikanan->$kolom ?? 'TIDAK DIISI';
                $row[$nama] = $nilai;
            }

            $writer->addRow($row);
        }

        // Tutup writer dan kembalikan file untuk diunduh
        $writer->close();

        return response()->download($path);
    }
}
