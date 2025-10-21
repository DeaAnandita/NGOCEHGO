<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataBangunKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            '3374123400000001',
        ];

        foreach ($records as $kk) {
            $databangunkeluarga = ['no_kk' => $kk];
            for ($i = 1; $i <= 51; $i++) {
                // 1 = punya, 0 = tidak punya (random)
                $databangunkeluarga["bangunkeluarga_$i"] = rand(0, 3);
            }

            DB::table('data_bangunkeluarga')->insert($databangunkeluarga);
        }
    }
}
