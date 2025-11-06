<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataBangunKeluarga;
use Illuminate\Support\Facades\DB;

class DataBangunKeluargaExport
{
    public static function export()
    {
        $filename = 'data_bangun_keluarga_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Ambil master bangun keluarga tipe jawaban = 1 (PILIHAN)
        $masterList = DB::table('master_pembangunankeluarga')
            ->where('kdtypejawab', 1)
            ->orderBy('kdpembangunankeluarga')
            ->pluck('pembangunankeluarga', 'kdpembangunankeluarga')
            ->toArray();

        // Ambil master jawaban
        $jawabanList = DB::table('master_jawabbangun')
            ->pluck('jawabbangun', 'kdjawabbangun')
            ->toArray();

        // Header Excel: No KK + nama indikator
        $header = array_merge(['No KK'], array_values($masterList));
        $writer->addHeader($header);

        // Ambil semua data bangun keluarga
        $dataKeluarga = DataBangunKeluarga::all();

        foreach ($dataKeluarga as $keluarga) {
            $row = ['No KK' => $keluarga->no_kk];

            // Loop tiap master bangun keluarga PILIHAN
            foreach ($masterList as $kode => $nama) {
                $kolom = "bangunkeluarga_{$kode}";
                $idJawab = $keluarga->$kolom ?? 0; // default 0
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}
