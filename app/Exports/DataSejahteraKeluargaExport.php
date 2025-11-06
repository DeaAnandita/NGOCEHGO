<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataSejahteraKeluarga;

class DataSejahteraKeluargaExport
{
    public static function export()
    {
        $filename = 'data_sejahtera_keluarga_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Header kolom
        $header = [
            'No KK',
            'Nama Keluarga',
            'Konsumsi Rokok per Hari',
            'Frekuensi Makan per Hari',
            'Durasi Hiburan per Hari',
            'Pengeluaran Harian (Rp)',
            'Pendapatan Bulanan Suami (Rp)',
            'Pendapatan Bulanan Istri (Rp)',
            'Total Pendapatan Keluarga (Rp)',
            'Kepemilikan Kendaraan',
        ];

        $writer->addHeader($header);

        // Ambil semua data sejahtera + relasi keluarga
        $data = DataSejahteraKeluarga::with('keluarga')->get();

        foreach ($data as $item) {
            $writer->addRow([
                'No KK' => $item->no_kk,
                'Nama Keluarga' => $item->keluarga->keluarga_kepalakeluarga ?? 'Tidak Diketahui',
                'Konsumsi Rokok per Hari' => $item->sejahterakeluarga_61 ?? '-',
                'Frekuensi Makan per Hari' => $item->sejahterakeluarga_62 ?? '-',
                'Durasi Hiburan per Hari' => $item->sejahterakeluarga_63 ?? '-',
                'Pengeluaran Harian (Rp)' => $item->sejahterakeluarga_64 ?? '-',
                'Pendapatan Bulanan Suami (Rp)' => $item->sejahterakeluarga_65 ?? '-',
                'Pendapatan Bulanan Istri (Rp)' => $item->sejahterakeluarga_66 ?? '-',
                'Total Pendapatan Keluarga (Rp)' => $item->sejahterakeluarga_67 ?? '-',
                'Kepemilikan Kendaraan' => $item->sejahterakeluarga_68 ?? '-',
            ]);
        }

        $writer->close();

        return response()->download($path);
    }
}
