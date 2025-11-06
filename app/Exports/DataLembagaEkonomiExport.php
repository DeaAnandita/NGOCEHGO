<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataLembagaEkonomi;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemek;

class DataLembagaEkonomiExport
{
    public static function export()
    {
        $filename = 'data_lembaga_ekonomi_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Ambil daftar lembaga pemerintahan ekonomi (kdjenislembaga = 4)
        // Urut sesuai tampilan web (lemek_1 - lemek_75)
        $lembagaList = MasterLembaga::where('kdjenislembaga', 4)
            ->orderBy('kdlembaga')
            ->pluck('lembaga', 'kdlembaga')
            ->values() // reset key ke 0,1,2...
            ->toArray();

        // Ambil daftar jawaban lembaga ekonomi
        $jawabanList = MasterJawabLemek::orderBy('kdjawablemek')
            ->pluck('jawablemek', 'kdjawablemek')
            ->toArray();

        // Header dinamis
        $header = array_merge(['NIK', 'Nama Lengkap'], $lembagaList);
        $writer->addHeader($header);

        // Ambil data lembaga ekonmi
        $dataLembaga = DataLembagaEkonomi::with('penduduk')->orderBy('nik')->get();

        foreach ($dataLembaga as $data) {
            $row = [
                'NIK' => $data->nik ?? '-',
                'Nama Lengkap' => $data->penduduk->penduduk_namalengkap ?? '-',
            ];

            // Loop urut sesuai kolom lemek_1 .. lemek_75
            foreach ($lembagaList as $index => $nama) {
                $kolom = 'lemek_' . ($index + 1);
                $idJawab = $data->$kolom ?? 0;
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}