<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSosialEkonomiSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_sosialekonomi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil 15 NIK dari tabel data_penduduk
        $nikList = DB::table('data_penduduk')->limit(15)->pluck('nik');

        $records = [];
        foreach ($nikList as $nik) {
            $records[] = [
                'nik' => $nik,
                'kdpartisipasisekolah' => fake()->numberBetween(1, 3),  // 1: masih sekolah, 2: tidak sekolah, 3: tamat
                'kdtingkatsulitdisabilitas' => fake()->numberBetween(1, 3),
                'kdstatuskedudukankerja' => fake()->numberBetween(1, 5),
                'kdijasahterakhir' => fake()->numberBetween(1, 6),
                'kdpenyakitkronis' => fake()->numberBetween(1, 4),
                'kdpendapatanperbulan' => fake()->numberBetween(1, 5),
                'kdjenisdisabilitas' => fake()->numberBetween(1, 3),
                'kdlapanganusaha' => fake()->numberBetween(1, 5),
                'kdimunisasi' => fake()->numberBetween(1, 2),
            ];
        }

        DB::table('data_sosialekonomi')->insert($records);
    }
}
