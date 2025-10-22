<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLembagaEkonomiSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            '3374123400000001',
            '3374123400000002',
        ];

        foreach ($records as $nik) {
            $lembagaekonomi= ['nik' => $nik];
            for ($i = 1; $i <= 75; $i++) {
                // 1 = punya, 0 = tidak punya (random)
                $lembagaekonomi["lemek_$i"] = rand(1,4);
            }

            DB::table('data_lembagaekonomi')->insert($lembagaekonomi);
        }
    }
}
