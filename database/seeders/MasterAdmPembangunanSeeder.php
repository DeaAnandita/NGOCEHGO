<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAdmPembangunanSeeder extends Seeder
{
    public function run(): void
    {
        // =======================
        // MASTER SASARAN
        // =======================
        DB::table('master_sasaran')->insert([
            ['kdsasaran' => 1, 'sasaran' => 'Keluarga Miskin'],
            ['kdsasaran' => 2, 'sasaran' => 'Lansia'],
            ['kdsasaran' => 3, 'sasaran' => 'Disabilitas'],
            ['kdsasaran' => 4, 'sasaran' => 'UMKM'],
            ['kdsasaran' => 5, 'sasaran' => 'Petani'],
            ['kdsasaran' => 6, 'sasaran' => 'Nelayan'],
            ['kdsasaran' => 7, 'sasaran' => 'Ibu Hamil'],
            ['kdsasaran' => 8, 'sasaran' => 'Balita'],
        ]);

        // =======================
        // MASTER BANTUAN
        // =======================
        DB::table('master_bantuan')->insert([
            ['kdbantuan' => 1, 'bantuan' => 'BLT'],
            ['kdbantuan' => 2, 'bantuan' => 'Sembako'],
            ['kdbantuan' => 3, 'bantuan' => 'Bibit'],
            ['kdbantuan' => 4, 'bantuan' => 'Pupuk'],
            ['kdbantuan' => 5, 'bantuan' => 'Bedah Rumah'],
            ['kdbantuan' => 6, 'bantuan' => 'Alat Usaha'],
            ['kdbantuan' => 7, 'bantuan' => 'Bantuan Tunai'],
            ['kdbantuan' => 8, 'bantuan' => 'Bantuan Non Tunai'],
        ]);

        // =======================
        // MASTER PENDIDIKAN
        // =======================
        DB::table('master_pendidikan')->insert([
            ['kdpendidikan' => 1, 'pendidikan' => 'Tidak Sekolah'],
            ['kdpendidikan' => 2, 'pendidikan' => 'SD'],
            ['kdpendidikan' => 3, 'pendidikan' => 'SMP'],
            ['kdpendidikan' => 4, 'pendidikan' => 'SMA'],
            ['kdpendidikan' => 5, 'pendidikan' => 'D3'],
            ['kdpendidikan' => 6, 'pendidikan' => 'S1'],
            ['kdpendidikan' => 7, 'pendidikan' => 'S2'],
        ]);

        // =======================
        // MASTER BIDANG KADER
        // =======================
        DB::table('master_kader_bidang')->insert([
            ['kdbidang' => 1, 'bidang' => 'Posyandu'],
            ['kdbidang' => 2, 'bidang' => 'PKK'],
            ['kdbidang' => 3, 'bidang' => 'Karang Taruna'],
            ['kdbidang' => 4, 'bidang' => 'Kesehatan'],
            ['kdbidang' => 5, 'bidang' => 'Pendidikan'],
            ['kdbidang' => 6, 'bidang' => 'Lingkungan'],
            ['kdbidang' => 7, 'bidang' => 'Sosial'],
        ]);

        // =======================
        // MASTER STATUS KADER
        // =======================
        DB::table('master_status_kader')->insert([
            ['kdstatuskader' => 1, 'statuskader' => 'Aktif'],
            ['kdstatuskader' => 2, 'statuskader' => 'Nonaktif'],
        ]);

        // =======================
        // MASTER KEGIATAN
        // =======================
        DB::table('master_kegiatan')->insert([
            ['kdkegiatan' => 1, 'kegiatan' => 'Pembangunan Jalan'],
            ['kdkegiatan' => 2, 'kegiatan' => 'Drainase'],
            ['kdkegiatan' => 3, 'kegiatan' => 'Irigasi'],
            ['kdkegiatan' => 4, 'kegiatan' => 'Balai Desa'],
            ['kdkegiatan' => 5, 'kegiatan' => 'Posyandu'],
            ['kdkegiatan' => 6, 'kegiatan' => 'Gedung PAUD'],
            ['kdkegiatan' => 7, 'kegiatan' => 'MCK'],
            ['kdkegiatan' => 8, 'kegiatan' => 'Rabat Beton'],
        ]);

        // =======================
        // MASTER LOKASI
        // =======================
        DB::table('master_lokasi')->insert([
            ['kdlokasi' => 1, 'lokasi' => 'Dusun I'],
            ['kdlokasi' => 2, 'lokasi' => 'Dusun II'],
            ['kdlokasi' => 3, 'lokasi' => 'Dusun III'],
            ['kdlokasi' => 4, 'lokasi' => 'RW 01'],
            ['kdlokasi' => 5, 'lokasi' => 'RW 02'],
            ['kdlokasi' => 6, 'lokasi' => 'RW 03'],
            ['kdlokasi' => 7, 'lokasi' => 'RT 01'],
            ['kdlokasi' => 8, 'lokasi' => 'RT 02'],
        ]);

        // =======================
        // MASTER PELAKSANA
        // =======================
        DB::table('master_pelaksana')->insert([
            ['kdpelaksana' => 1, 'pelaksana' => 'TPK Desa'],
            ['kdpelaksana' => 2, 'pelaksana' => 'BUMDes'],
            ['kdpelaksana' => 3, 'pelaksana' => 'Karang Taruna'],
            ['kdpelaksana' => 4, 'pelaksana' => 'CV Karya Bangun'],
            ['kdpelaksana' => 5, 'pelaksana' => 'Swakelola Desa'],
        ]);
    }
}
