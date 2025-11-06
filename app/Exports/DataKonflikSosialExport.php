<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataKonflikSosial;
use App\Models\MasterKonflikSosial;
use App\Models\MasterJawabKonflik;

class DataKonflikSosialExport
{
    public static function export()
    {
        $filename = 'data_konflik_sosial_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Ambil master konflik sosial dan jawaban
        $konflikList = MasterKonflikSosial::orderBy('kdkonfliksosial')
            ->pluck('konfliksosial', 'kdkonfliksosial')
            ->toArray();

        $jawabanList = MasterJawabKonflik::pluck('jawabkonflik', 'kdjawabkonflik')->toArray();

        // Buat header: No KK + Nama Kepala Keluarga + 32 konflik
        $header = array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($konflikList));
        $writer->addHeader($header);

        // Ambil semua data konflik sosial beserta relasi keluarga
        $dataKeluarga = DataKonflikSosial::with('keluarga')->get();

        foreach ($dataKeluarga as $keluarga) {
            $row = [
                'No KK' => $keluarga->no_kk,
                'Nama Kepala Keluarga' => $keluarga->keluarga->keluarga_kepalakeluarga ?? '-',
            ];

            // Loop tiap kolom konflik sosial
            foreach ($konflikList as $kode => $nama) {
                $kolom = "konfliksosial_{$kode}";
                $idJawab = $keluarga->$kolom ?? 0;
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}
