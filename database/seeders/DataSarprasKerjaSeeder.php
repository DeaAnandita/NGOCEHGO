<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSarprasKerjaSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        DB::table('data_sarpraskerja')->truncate();

        // Contoh data dummy
        DB::table('data_sarpraskerja')->insert([
            [
                'no_kk' => '3374123400000001',
                'sarpraskerja_1' => 2,
                'sarpraskerja_2' => 3,
                'sarpraskerja_3' => 1,
                'sarpraskerja_4' => 5,
                'sarpraskerja_5' => 2,
                'sarpraskerja_6' => 6,
                'sarpraskerja_7' => 3,
                'sarpraskerja_8' => 2,
                'sarpraskerja_9' => 1,
                'sarpraskerja_10' => 4,
                'sarpraskerja_11' => 5,
                'sarpraskerja_12' => 3,
                'sarpraskerja_13' => 2,
                'sarpraskerja_14' => 1,
                'sarpraskerja_15' => 6,
                'sarpraskerja_16' => 3,
                'sarpraskerja_17' => 2,
                'sarpraskerja_18' => 1,
                'sarpraskerja_19' => 4,
                'sarpraskerja_20' => 5,
                'sarpraskerja_21' => 3,
                'sarpraskerja_22' => 2,
                'sarpraskerja_23' => 1,
                'sarpraskerja_24' => 5,
                'sarpraskerja_25' => 3,
            ],
        ]);
    }
}
