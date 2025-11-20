<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKelahiranSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key sementara agar bisa truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_kelahiran')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil 15 NIK dari data_penduduk (anggap 15 bayi lahir)
        $nikList = DB::table('data_penduduk')->limit(15)->pluck('nik');

        $records = [];

        foreach ($nikList as $index => $nik) {
            $records[] = [
                'nik' => $nik,
                'kdtempatpersalinan' => fake()->numberBetween(1, 3), // 1=RS, 2=Puskesmas, 3=Rumah
                'kdjeniskelahiran' => fake()->numberBetween(1, 2),   // 1=Tunggal, 2=Kembar
                'kdpertolonganpersalinan' => fake()->numberBetween(1, 3), // 1=Dokter, 2=Bidan, 3=Tenaga Medis
                'kelahiran_jamkelahiran' => fake()->time('H:i:s'),
                'kelahiran_kelahiranke' => fake()->numberBetween(1, 4),
                'kelahiran_berat' => fake()->numberBetween(2700, 4000), // gram
                'kelahiran_panjang' => fake()->numberBetween(45, 55),   // cm
                'kelahiran_nikibu' => DB::table('data_penduduk')->inRandomOrder()->value('nik'),
                'kelahiran_nikayah' => DB::table('data_penduduk')->inRandomOrder()->value('nik'),
                'created_by' => 1,
            ];
        }

        DB::table('data_kelahiran')->insert($records);
    }
}
