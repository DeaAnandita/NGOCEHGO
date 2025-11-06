<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataPenduduk;
use App\Models\DataKeluarga;
use App\Models\MasterJenisKelamin;
use App\Models\MasterAgama;
use App\Models\MasterHubunganKeluarga;
use App\Models\MasterHubunganKepalaKeluarga;
use App\Models\MasterStatusKawin;
use App\Models\MasterAktaNikah;
use App\Models\MasterTercantumDalamKk;
use App\Models\MasterStatusTinggal;
use App\Models\MasterKartuIdentitas;
use App\Models\MasterMutasiMasuk;
use App\Models\MasterPekerjaan;

class DataPendudukExport
{
    public static function export()
    {
        $filename = 'data_penduduk_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Header kolom sesuai field pada rancangan
        $header = [
            'No KK',
            'NIK',
            'Nama Lengkap',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Status Hubungan Keluarga',
            'Status Hubungan Dalam KK',
            'Status Kawin',
            'Kepemilikan Akta Nikah',
            'Tercantum Dalam KK',
            'Status Tinggal',
            'Kartu Identitas',
            'Golongan Darah',
            'Nama Ayah',
            'Nama Ibu',
            'Pekerjaan',
            'Nama Tempat Bekerja',
            'Kewarganegaraan',
            'Tanggal Mutasi',
            'Mutasi Masuk',
        ];

        $writer->addHeader($header);

        // Ambil data master untuk relasi
        $master_jeniskelamin = MasterJenisKelamin::pluck('jeniskelamin', 'kdjeniskelamin')->toArray();
        $master_agama = MasterAgama::pluck('agama', 'kdagama')->toArray();
        $master_hubkel = MasterHubunganKeluarga::pluck('hubungankeluarga', 'kdhubungankeluarga')->toArray();
        $master_hubkk = MasterHubunganKepalaKeluarga::pluck('hubungankepalakeluarga', 'kdhubungankepalakeluarga')->toArray();
        $master_statuskawin = MasterStatusKawin::pluck('statuskawin', 'kdstatuskawin')->toArray();
        $master_aktanikah = MasterAktaNikah::pluck('aktanikah', 'kdaktanikah')->toArray();
        $master_tercantumkk = MasterTercantumDalamKk::pluck('tercantumdalamkk', 'kdtercantumdalamkk')->toArray();
        $master_statustinggal = MasterStatusTinggal::pluck('statustinggal', 'kdstatustinggal')->toArray();
        $master_kartuidentitas = MasterKartuIdentitas::pluck('kartuidentitas', 'kdkartuidentitas')->toArray();
        $master_pekerjaan = MasterPekerjaan::pluck('pekerjaan', 'kdpekerjaan')->toArray();
        $master_mutasimasuk = MasterMutasiMasuk::pluck('mutasimasuk', 'kdmutasimasuk')->toArray();

        // Ambil seluruh data penduduk beserta relasi keluarga
        $dataPenduduk = DataPenduduk::with('keluarga')->get();

        foreach ($dataPenduduk as $p) {
            $row = [
                'No KK' => $p->no_kk ?? ($p->keluarga->no_kk ?? '-'),
                'NIK' => $p->nik,
                'Nama Lengkap' => $p->penduduk_namalengkap,
                'Tempat Lahir' => $p->penduduk_tempatlahir,
                'Tanggal Lahir' => $p->penduduk_tanggallahir,
                'Jenis Kelamin' => $master_jeniskelamin[$p->kdjeniskelamin] ?? '-',
                'Agama' => $master_agama[$p->kdagama] ?? '-',
                'Status Hubungan Keluarga' => $master_hubkel[$p->kdhubungankeluarga] ?? '-',
                'Status Hubungan Dalam KK' => $master_hubkk[$p->kdhubungankepalakeluarga] ?? '-',
                'Status Kawin' => $master_statuskawin[$p->kdstatuskawin] ?? '-',
                'Kepemilikan Akta Nikah' => $master_aktanikah[$p->kdaktanikah] ?? '-',
                'Tercantum Dalam KK' => $master_tercantumkk[$p->kdtercantumdalamkk] ?? '-',
                'Status Tinggal' => $master_statustinggal[$p->kdstatustinggal] ?? '-',
                'Kartu Identitas' => $master_kartuidentitas[$p->kdkartuidentitas] ?? '-',
                'Golongan Darah' => $p->penduduk_goldarah ?? '-',
                'Nama Ayah' => $p->penduduk_namaayah ?? '-',
                'Nama Ibu' => $p->penduduk_namaibu ?? '-',
                'Pekerjaan' => $master_pekerjaan[$p->kdpekerjaan] ?? '-',
                'Nama Tempat Bekerja' => $p->penduduk_namatempatbekerja ?? '-',
                'Kewarganegaraan' => $p->kewarganegaraan ?? 'INDONESIA',
                'Tanggal Mutasi' => $p->penduduk_tanggalmutasi ?? '-',
                'Mutasi Masuk' => $master_mutasimasuk[$p->kdmutasimasuk] ?? '-',
            ];

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}
