<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataKualitasBayi;
use App\Models\MasterKualitasBayi;
use App\Models\MasterJawabKualitasBayi;
use Illuminate\Support\Facades\DB;

class DataKualitasBayiExport
{
    public static function export()
    {
        $filename = 'data_kualitas_bayi_' . date('Ymd_His') . '.xlsx';
        $folder = storage_path('app/exports');

        // Pastikan folder tujuan ada
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $path = "{$folder}/{$filename}";
        $writer = SimpleExcelWriter::create($path);

        // Ambil daftar pertanyaan & jawaban master
        $bayiList = MasterKualitasBayi::orderBy('kdkualitasbayi')
            ->pluck('kualitasbayi', 'kdkualitasbayi')
            ->toArray();

        $jawabanList = MasterJawabKualitasBayi::pluck('jawabkualitasbayi', 'kdjawabkualitasbayi')
            ->toArray();

        // Header Excel (No KK + Nama Kepala Keluarga + pertanyaan)
        $header = array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($bayiList));
        $writer->addHeader($header);

        // Ambil data dengan relasi ke data_keluarga (pastikan nama tabel & kolom sesuai migration)
        $dataKeluarga = DataKualitasBayi::join('data_keluarga', 'data_keluarga.no_kk', '=', 'data_kualitasbayi.no_kk')
            ->select('data_kualitasbayi.*', 'data_keluarga.keluarga_kepalakeluarga')
            ->get();

        foreach ($dataKeluarga as $kualitasbayi) {
            $row = [
                'No KK' => $kualitasbayi->no_kk,
                // ambil dari kolom migration: keluarga_kepalakeluarga
                'Nama Kepala Keluarga' => $kualitasbayi->keluarga_kepalakeluarga ?? '-',
            ];

            // Loop pertanyaan (1–13)
            foreach ($bayiList as $kode => $nama) {
                $kolom = "kualitasbayi_{$kode}";
                $idJawab = $kualitasbayi->$kolom ?? 0;
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        // File otomatis dihapus setelah diunduh
        return response()->download($path)->deleteFileAfterSend(true);
    }
}
