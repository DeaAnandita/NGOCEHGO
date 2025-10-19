<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetLahanSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            '3374123400000001',
            '3374123400000002',
        ];

        foreach ($records as $kk) {
            $asetLahan = ['no_kk' => $kk];
            for ($i = 1; $i <= 10; $i++) {
                // 1 = punya, 0 = tidak punya (random)
                $asetLahan["asetlahan_$i"] = rand(0, 1);
            }

            DB::table('data_asetlahan')->insert($asetLahan);
        }
    }
}
