<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataKelahiran;
use App\Models\MasterTempatPersalinan;
use App\Models\MasterJenisKelahiran;
use App\Models\MasterPertolonganPersalinan;
//use App\Models\MasterProvinsi;
//use App\Models\MasterKabupaten;
//use App\Models\MasterKecamatan;
//use App\Models\MasterDesa;

class DataKelahiranExport
{
    public static function export()
    {
        $filename = 'data_kelahiran_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Header kolom sesuai rancangan
        $header = [
            'NIK Bayi',
            'Nama Bayi',
            'Tanggal Lahir',
            'Jam Kelahiran',
            'Tempat Persalinan',
            'Jenis Kelahiran',
            'Pertolongan Persalinan',
            'Kelahiran Ke',
            'Berat (gram)',
            'Panjang (cm)',
            'NIK Ibu',
            'Nama Ibu',
            'NIK Ayah',
            'Nama Ayah',
           // 'Alamat Lengkap (RT/RW)',
           // 'Provinsi',
           // 'Kabupaten',
           // 'Kecamatan',
           // 'Desa/Kelurahan',
        ];

        $writer->addHeader($header);

        // Ambil data master
        $m_tempat = MasterTempatPersalinan::pluck('tempatpersalinan', 'kdtempatpersalinan')->toArray();
        $m_jenis = MasterJenisKelahiran::pluck('jeniskelahiran', 'kdjeniskelahiran')->toArray();
        $m_pertolongan = MasterPertolonganPersalinan::pluck('pertolonganpersalinan', 'kdpertolonganpersalinan')->toArray();
        //$m_provinsi = MasterProvinsi::pluck('provinsi', 'kdprovinsi')->toArray();
        //$m_kabupaten = MasterKabupaten::pluck('kabupaten', 'kdkabupaten')->toArray();
        //$m_kecamatan = MasterKecamatan::pluck('kecamatan', 'kdkecamatan')->toArray();
        //$m_desa = MasterDesa::pluck('desa', 'kddesa')->toArray();

        // Ambil seluruh data kelahiran dengan relasi
        $dataKelahiran = DataKelahiran::with(['penduduk', 'ibu', 'ayah'])->get();

        foreach ($dataKelahiran as $k) {
            $row = [
                'NIK Bayi' => $k->nik,
                'Nama Bayi' => $k->penduduk->penduduk_namalengkap ?? '-',
                'Tanggal Lahir' => $k->penduduk->penduduk_tanggallahir ?? '-',
                'Jam Kelahiran' => $k->kelahiran_jamkelahiran ?? '-',
                'Tempat Persalinan' => $m_tempat[$k->kdtempatpersalinan] ?? '-',
                'Jenis Kelahiran' => $m_jenis[$k->kdjeniskelahiran] ?? '-',
                'Pertolongan Persalinan' => $m_pertolongan[$k->kdpertolonganpersalinan] ?? '-',
                'Kelahiran Ke' => $k->kelahiran_kelahiranke ?? '-',
                'Berat (gram)' => $k->kelahiran_berat ?? '-',
                'Panjang (cm)' => $k->kelahiran_panjang ?? '-',
                'NIK Ibu' => $k->kelahiran_nikibu ?? '-',
                'Nama Ibu' => $k->ibu->penduduk_namalengkap ?? '-',
                'NIK Ayah' => $k->kelahiran_nikayah ?? '-',
                'Nama Ayah' => $k->ayah->penduduk_namalengkap ?? '-',
                //'Alamat Lengkap (RT/RW)' => "RT {$k->kelahiran_rt}/RW {$k->kelahiran_rw}",
                //'Provinsi' => $m_provinsi[$k->kdprovinsi] ?? '-',
                //'Kabupaten' => $m_kabupaten[$k->kdkabupaten] ?? '-',
                //'Kecamatan' => $m_kecamatan[$k->kdkecamatan] ?? '-',
                //'Desa/Kelurahan' => $m_desa[$k->kddesa] ?? '-',
            ];

            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($path);
    }
}
