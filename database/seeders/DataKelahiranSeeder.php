<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKelahiranSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key sementara agar bisa truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_kelahiran')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data kelahiran sesuai dengan data_penduduk
        DB::table('data_kelahiran')->insert([
            [
                'nik' => '3374123400000001', // NIK bayi baru
                'kdtempatpersalinan' => 1, // Rumah Sakit
                'kdjeniskelahiran' => 1, // Tunggal
                'kdpertolonganpersalinan' => 1, // Dokter
                'kelahiran_jamkelahiran' => '07:45:00',
                'kelahiran_kelahiranke' => 1,
                'kelahiran_berat' => 3100,
                'kelahiran_panjang' => 49,
                'kelahiran_nikibu' => '3374123400000002', // Siti Aminah
                'kelahiran_nikayah' => '3374123400000003', // Budi Santoso
                'kelahiran_rw' => '03',
                'kelahiran_rt' => '01',
                'kdprovinsi' => 1,
                'kdkabupaten' => 1,
                'kdkecamatan' => 1,
                'kddesa' => 1,
                'created_by' => 1,
            ],
            [
                'nik' => '3374123400000002', // Anak Ahmad Fauzi & Rohani
                'kdtempatpersalinan' => 2, // Puskesmas
                'kdjeniskelahiran' => 1, // Tunggal
                'kdpertolonganpersalinan' => 2, // Bidan
                'kelahiran_jamkelahiran' => '14:30:00',
                'kelahiran_kelahiranke' => 2,
                'kelahiran_berat' => 3200,
                'kelahiran_panjang' => 50,
                'kelahiran_nikibu' => '3374123400000003', // Ibu = Rohani
                'kelahiran_nikayah' => '3374123400000001', // Ayah = Ahmad Fauzi (sementara disamakan)
                'kelahiran_rw' => '02',
                'kelahiran_rt' => '05',
                'kdprovinsi' => 1,
                'kdkabupaten' => 1,
                'kdkecamatan' => 3,
                'kddesa' => 3,
                'created_by' => 1,
            ],
        ]);
    }
}
