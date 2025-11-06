<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataUsahaArt;
use App\Models\MasterLapanganUsaha;
use App\Models\MasterTempatUsaha;
use App\Models\MasterOmsetUsaha;
use Illuminate\Support\Facades\Response;

class DataUsahaArtExport
{
    public static function export()
    {
        try {
            // === Nama file dan path penyimpanan ===
            $filename = 'data_usaha_art_' . date('Ymd_His') . '.xlsx';
            $path = storage_path("app/public/{$filename}");

            // === Buat writer Excel ===
            $writer = SimpleExcelWriter::create($path);

            // === Ambil daftar master data ===
            $lapanganUsahaList = MasterLapanganUsaha::orderBy('kdlapanganusaha')
                ->pluck('lapanganusaha', 'kdlapanganusaha')
                ->toArray();

            $tempatUsahaList = MasterTempatUsaha::orderBy('kdtempatusaha')
                ->pluck('tempatusaha', 'kdtempatusaha')
                ->toArray();

            $omsetUsahaList = MasterOmsetUsaha::orderBy('kdomsetusaha')
                ->pluck('omsetusaha', 'kdomsetusaha')
                ->toArray();

            // === Header kolom Excel ===
            $header = [
                'NIK',
                'Nama Lengkap',
                'Nama Usaha',
                'Lapangan Usaha',
                'Tempat Usaha',
                'Omset Usaha',
                'Jumlah Pekerja',
            ];

            $writer->addHeader($header);

            // === Ambil data usaha ART + relasi penduduk ===
            $dataUsahaArt = DataUsahaArt::with('penduduk')->get();

            // === Tambahkan baris data ===
            foreach ($dataUsahaArt as $data) {
                $row = [
                    'NIK' => $data->nik ?? '-',
                    'Nama Lengkap' => $data->penduduk->penduduk_namalengkap ?? '-',
                    'Nama Usaha' => $data->usahaart_namausaha ?? '-',
                    'Lapangan Usaha' => $lapanganUsahaList[$data->kdlapanganusaha ?? 0] ?? 'TIDAK DIISI',
                    'Tempat Usaha' => $tempatUsahaList[$data->kdtempatusaha ?? 0] ?? 'TIDAK DIISI',
                    'Omset Usaha' => $omsetUsahaList[$data->kdomsetusaha ?? 0] ?? 'TIDAK DIISI',
                    'Jumlah Pekerja' => $data->usahaart_jumlahpekerja ?? '0',
                ];

                $writer->addRow($row);
            }

            $writer->close();

            // === Kembalikan response download ===
            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            return Response::make('Terjadi kesalahan saat mengekspor data usaha ART: ' . $th->getMessage(), 500);
        }
    }
}
