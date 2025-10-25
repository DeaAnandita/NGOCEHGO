<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPrasaranaDasarSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_prasaranadasar')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil 15 no_kk dari tabel data_keluarga
        $noKKList = DB::table('data_keluarga')->limit(15)->pluck('no_kk');

        $records = [];

        foreach ($noKKList as $noKK) {
            $records[] = [
                'no_kk' => $noKK,
                'kdstatuspemilikbangunan' => fake()->numberBetween(1, 8), // 1 = Milik sendiri
                'kdstatuspemiliklahan' => fake()->numberBetween(1, 7),    // 1 = Milik sendiri
                'kdjenisfisikbangunan' => fake()->numberBetween(1, 3),    // 1 = Permanen
                'kdjenislantaibangunan' => fake()->numberBetween(1, 9),
                'kdkondisilantaibangunan' => fake()->numberBetween(1, 2),
                'kdjenisdindingbangunan' => fake()->numberBetween(1, 7),
                'kdkondisidindingbangunan' => fake()->numberBetween(1, 2),
                'kdjenisatapbangunan' => fake()->numberBetween(1, 2),
                'kdkondisiatapbangunan' => fake()->numberBetween(1, 2),
                'kdsumberairminum' => fake()->numberBetween(1, 3),
                'kdkondisisumberair' => fake()->numberBetween(1, 4),
                'kdcaraperolehanair' => fake()->numberBetween(1, 3),
                'kdsumberpeneranganutama' => fake()->numberBetween(1, 3),
                'kdsumberdayaterpasang' => fake()->numberBetween(1, 6),
                'kdbahanbakarmemasak' => fake()->numberBetween(1, 9),
                'kdfasilitastempatbab' => fake()->numberBetween(0, 3),
                'kdpembuanganakhirtinja' => fake()->numberBetween(1, 6),
                'kdcarapembuangansampah' => fake()->numberBetween(1, 7),
                'kdmanfaatmataair' => fake()->numberBetween(1, 8),
                'prasdas_luaslantai' => fake()->randomFloat(1, 36, 120), // luas antara 36–120 m2
                'prasdas_jumlahkamar' => fake()->numberBetween(1, 5),
            ];
        }

        DB::table('data_prasaranadasar')->insert($records);
    }
}
