<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataKeluarga;
use App\Models\MasterDusun;
use App\Models\MasterMutasimasuk;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;

class DataKeluargaExport
{
    public static function export()
    {
        $filename = 'data_keluarga_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // 🔹 Header kolom
        $header = [
            'No KK',
            'Kepala Keluarga',
            'Tanggal Mutasi',
            'Jenis Mutasi',
            'Dusun',
            'RW',
            'RT',
            'Alamat Lengkap',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Desa',
        ];

        $writer->addHeader($header);

        // 🔹 Ambil semua data keluarga
        $dataKeluarga = DataKeluarga::all();

        foreach ($dataKeluarga as $keluarga) {
            $row = [
                'No KK' => $keluarga->no_kk,
                'Kepala Keluarga' => $keluarga->keluarga_kepalakeluarga,
                'Tanggal Mutasi' => $keluarga->keluarga_tanggalmutasi,
                'Jenis Mutasi' => optional(MasterMutasimasuk::find($keluarga->kdmutasimasuk))->mutasimasuk ?? '-',
                'Dusun' => optional(MasterDusun::find($keluarga->kddusun))->dusun ?? '-',
                'RW' => $keluarga->keluarga_rw,
                'RT' => $keluarga->keluarga_rt,
                'Alamat Lengkap' => $keluarga->keluarga_alamatlengkap,
                'Provinsi' => optional(MasterProvinsi::find($keluarga->kdprovinsi))->provinsi ?? '-',
                'Kabupaten' => optional(MasterKabupaten::find($keluarga->kdkabupaten))->kabupaten ?? '-',
                'Kecamatan' => optional(MasterKecamatan::find($keluarga->kdkecamatan))->kecamatan ?? '-',
                'Desa' => optional(MasterDesa::find($keluarga->kddesa))->desa ?? '-',
            ];

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}
