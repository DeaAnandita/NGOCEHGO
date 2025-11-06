<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataSarprasKerja;
use App\Models\MasterSarprasKerja;
use App\Models\MasterJawabSarpras;
use App\Models\DataKeluarga; // <- untuk nama kepala keluarga

class DataSarprasKerjaExport
{
    public static function export()
    {
        $filename = 'data_sarpraskerja_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Ambil master sarpras kerja dan jawaban sarpras
        $sarprasList = MasterSarprasKerja::orderBy('kdsarpraskerja')
            ->pluck('sarpraskerja', 'kdsarpraskerja')
            ->toArray();

        $jawabanList = MasterJawabSarpras::pluck('jawabsarpras', 'kdjawabsarpras')->toArray();

        // Buat header dinamis
        $header = array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($sarprasList));
        $writer->addHeader($header);

        // Ambil semua data sarpras kerja
        $dataSarpras = DataSarprasKerja::all();

        foreach ($dataSarpras as $item) {
            // Ambil nama kepala keluarga
            $keluarga = DataKeluarga::where('no_kk', $item->no_kk)->first();
            $namaKepala = $keluarga->keluarga_kepalakeluarga ?? '-';

            $row = [
                'No KK' => $item->no_kk,
                'Nama Kepala Keluarga' => $namaKepala,
            ];

            // Loop tiap sarpras kerja (1-25)
            foreach ($sarprasList as $kode => $nama) {
                $kolom = "sarpraskerja_{$kode}";
                $idJawab = $item->$kolom ?? 1; // default 1 = TIDAK DIISI
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}
