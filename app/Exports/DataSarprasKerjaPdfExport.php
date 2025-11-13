<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataSarprasKerja;
use App\Models\DataKeluarga;
use App\Models\MasterSarprasKerja;
use App\Models\MasterJawabSarpras;

class DataSarprasKerjaPdfExport
{
    public static function export()
    {
        $filename = 'data_sarpraskerja_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // 🔹 Ambil master data
        $masterSarpras = MasterSarprasKerja::orderBy('kdsarpraskerja')
            ->pluck('sarpraskerja', 'kdsarpraskerja')
            ->toArray();

        $masterJawab = MasterJawabSarpras::pluck('jawabsarpras', 'kdjawabsarpras')->toArray();

        // 🔹 Ambil semua kepala keluarga sekaligus
        $keluargas = DataKeluarga::pluck('keluarga_kepalakeluarga', 'no_kk')->toArray();

        // 🔹 Header kolom
        $header = array_merge(['No KK', 'Kepala Keluarga'], array_values($masterSarpras));
        $writer->addHeader($header);

        // 🔹 Ambil semua data sarpras kerja
        $dataSarpras = DataSarprasKerja::all();

        foreach ($dataSarpras as $row) {
            $namaKepala = $keluargas[$row->no_kk] ?? '-';

            $rowData = [
                'No KK' => $row->no_kk,
                'Kepala Keluarga' => $namaKepala,
            ];

            foreach ($masterSarpras as $kode => $nama) {
                $val = $row->{'sarpraskerja_'.$kode} ?? 0;
                $rowData[$nama] = $masterJawab[$val] ?? 'Tidak diisi';
            }

            $writer->addRow($rowData);
        }

        $writer->close();

        return response()->download($path);
    }
}
