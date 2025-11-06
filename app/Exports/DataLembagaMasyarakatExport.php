<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataLembagaMasyarakat;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemmas;

class DataLembagaMasyarakatExport
{
    public static function export()
    {
        $filename = 'data_lembaga_masyarakat_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Ambil daftar lembaga masyarakat (kdjenislembaga = 3, misal kode jenis lembaga masyarakat)
        // Urut sesuai tampilan web (lemmas_1 - lemmas_48)
        $lembagaList = MasterLembaga::where('kdjenislembaga', 3)
            ->orderBy('kdlembaga')
            ->pluck('lembaga', 'kdlembaga')
            ->values() // reset key ke 0,1,2...
            ->toArray();

        // Ambil daftar jawaban lembaga masyarakat
        $jawabanList = MasterJawabLemmas::orderBy('kdjawablemmas')
            ->pluck('jawablemmas', 'kdjawablemmas')
            ->toArray();

        // Header dinamis
        $header = array_merge(['NIK', 'Nama Lengkap'], $lembagaList);
        $writer->addHeader($header);

        // Ambil data lembaga masyarakat
        $dataLembaga = DataLembagaMasyarakat::with('penduduk')->orderBy('nik')->get();

        foreach ($dataLembaga as $data) {
            $row = [
                'NIK' => $data->nik ?? '-',
                'Nama Lengkap' => $data->penduduk->penduduk_namalengkap ?? '-',
            ];

            // Loop urut sesuai kolom lemmas_1 .. lemmas_48
            foreach ($lembagaList as $index => $nama) {
                $kolom = 'lemmas_' . ($index + 1);
                $idJawab = $data->$kolom ?? 0;
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}
