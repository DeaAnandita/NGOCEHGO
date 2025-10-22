<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLembagaMasyarakatSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            '3374123400000001',
            '3374123400000002',
        ];

        foreach ($records as $nik) {
            $lembagamasyarakat= ['nik' => $nik];
            for ($i = 1; $i <= 48; $i++) {
                // 1 = punya, 0 = tidak punya (random)
                $lembagamasyarakat["lemmas_$i"] = rand(1,4);
            }

            DB::table('data_lembagamasyarakat')->insert($lembagamasyarakat);
        }
    }
}
