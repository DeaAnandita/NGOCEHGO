<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetPerikananSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            '3374123400000001',
        ];

        foreach ($records as $kk) {
            $asetData = ['no_kk' => $kk];
            for ($i = 1; $i <= 6; $i++) {
                // 1 = punya, 0 = tidak punya (random untuk contoh)
                $asetData["asetperikanan_$i"] = rand(0, 10);
            }

            DB::table('data_asetperikanan')->insert($asetData);
        }
    }
}