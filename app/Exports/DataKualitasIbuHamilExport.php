<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataKualitasIbuHamil;
use App\Models\MasterKualitasIbuHamil;
use App\Models\MasterJawabKualitasIbuHamil;
use Illuminate\Support\Facades\DB;

class DataKualitasIbuHamilExport
{
    public static function export()
    {
        $filename = 'data_kualitas_ibu_hamil_' . date('Ymd_His') . '.xlsx';
        $folder = storage_path('app/exports');

        // Pastikan folder tujuan ada
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $path = "{$folder}/{$filename}";
        $writer = SimpleExcelWriter::create($path);

        // Ambil daftar pertanyaan & jawaban master
        $ibuhamilList = MasterKualitasIbuHamil::orderBy('kdkualitasibuhamil')
            ->pluck('kualitasibuhamil', 'kdkualitasibuhamil')
            ->toArray();

        $jawabanList = MasterJawabKualitasIbuHamil::pluck('jawabkualitasibuhamil', 'kdjawabkualitasibuhamil')
            ->toArray();

        // Header Excel (No KK + Nama Kepala Keluarga + pertanyaan)
        $header = array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($ibuhamilList));
        $writer->addHeader($header);

        // Ambil data dengan relasi ke data_keluarga (pastikan nama tabel & kolom sesuai migration)
        $dataKeluarga = DataKualitasIbuHamil::join('data_keluarga', 'data_keluarga.no_kk', '=', 'data_kualitasibuhamil.no_kk')
            ->select('data_kualitasibuhamil.*', 'data_keluarga.keluarga_kepalakeluarga')
            ->get();

        foreach ($dataKeluarga as $kualitasibuhamil) {
            $row = [
                'No KK' => $kualitasibuhamil->no_kk,
                // ambil dari kolom migration: keluarga_kepalakeluarga
                'Nama Kepala Keluarga' => $kualitasibuhamil->keluarga_kepalakeluarga ?? '-',
            ];

            // Loop pertanyaan (1–13)
            foreach ($ibuhamilList as $kode => $nama) {
                $kolom = "kualitasibuhamil_{$kode}";
                $idJawab = $kualitasibuhamil->$kolom ?? 0;
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        // File otomatis dihapus setelah diunduh
        return response()->download($path)->deleteFileAfterSend(true);
    }
}
