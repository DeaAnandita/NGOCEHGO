<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataLembagaDesa;
use App\Models\MasterLembaga;
use App\Models\MasterJawabLemdes;

class DataLembagaDesaExport
{
    public static function export()
    {
        $filename = 'data_lembaga_desa_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Ambil daftar lembaga pemerintahan desa (kdjenislembaga = 2)
        // Urut sesuai tampilan web (lemdes_1 - lemdes_9)
        $lembagaList = MasterLembaga::where('kdjenislembaga', 2)
            ->orderBy('kdlembaga')
            ->pluck('lembaga', 'kdlembaga')
            ->values() // reset key ke 0,1,2...
            ->toArray();

        // Ambil daftar jawaban lembaga desa
        $jawabanList = MasterJawabLemdes::orderBy('kdjawablemdes')
            ->pluck('jawablemdes', 'kdjawablemdes')
            ->toArray();

        // Header dinamis
        $header = array_merge(['NIK', 'Nama Lengkap'], $lembagaList);
        $writer->addHeader($header);

        // Ambil data lembaga desa
        $dataLembaga = DataLembagaDesa::with('penduduk')->orderBy('nik')->get();

        foreach ($dataLembaga as $data) {
            $row = [
                'NIK' => $data->nik ?? '-',
                'Nama Lengkap' => $data->penduduk->penduduk_namalengkap ?? '-',
            ];

            // Loop urut sesuai kolom lemdes_1 .. lemdes_9
            foreach ($lembagaList as $index => $nama) {
                $kolom = 'lemdes_' . ($index + 1);
                $idJawab = $data->$kolom ?? 0;
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}
