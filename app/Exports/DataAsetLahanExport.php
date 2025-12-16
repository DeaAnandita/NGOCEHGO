<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataAsetLahan;
use App\Models\MasterAsetLahan;
use App\Models\MasterJawabLahan;
use Illuminate\Support\Facades\Storage;

class DataAsetLahanExport
{
    public static function export()
    {
        $filename = 'data_aset_lahan_' . now()->format('Ymd_His') . '.xlsx';

        // 🔑 pakai temp file (INI PENTING)
        $tempPath = storage_path('app/temp');
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        $fullPath = $tempPath . '/' . $filename;

        $writer = SimpleExcelWriter::create($fullPath);

        $asetList = MasterAsetLahan::orderBy('kdasetlahan')
            ->pluck('asetlahan', 'kdasetlahan')
            ->toArray();

        $jawabanList = MasterJawabLahan::pluck('jawablahan', 'kdjawablahan')->toArray();

        $writer->addHeader(array_merge(['No KK'], array_values($asetList)));

        foreach (DataAsetLahan::cursor() as $lahan) {
            $row = ['No KK' => $lahan->no_kk];

            foreach ($asetList as $kode => $nama) {
                $kolom = "asetlahan_{$kode}";
                $row[$nama] = $jawabanList[$lahan->$kolom ?? 0] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($fullPath)->deleteFileAfterSend(true);
    }
}
