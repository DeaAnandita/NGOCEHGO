<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            '3374123400000001',
            '3374123400000002',
        ];

        foreach ($records as $kk) {
            $asetData = ['no_kk' => $kk];
            for ($i = 1; $i <= 42; $i++) {
                // 1 = punya, 0 = tidak punya (random untuk contoh)
                $asetData["asetkeluarga_$i"] = rand(0, 2);
            }

            DB::table('data_asetkeluarga')->insert($asetData);
        }
    }
}
