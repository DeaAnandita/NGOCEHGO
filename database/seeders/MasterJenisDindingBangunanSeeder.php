<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJenisDindingBangunanSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_jenisdindingbangunan')->truncate();

        // Insert data master
        DB::table('master_jenisdindingbangunan')->insert([
            ['kdjenisdindingbangunan' => 1, 'jenisdindingbangunan' => 'TEMBOK'],
            ['kdjenisdindingbangunan' => 2, 'jenisdindingbangunan' => 'KAYU'],
            ['kdjenisdindingbangunan' => 3, 'jenisdindingbangunan' => 'KALSIBOARD'],
            ['kdjenisdindingbangunan' => 4, 'jenisdindingbangunan' => 'TRIPLEK'],
            ['kdjenisdindingbangunan' => 5, 'jenisdindingbangunan' => 'BAMBU'],
            ['kdjenisdindingbangunan' => 6, 'jenisdindingbangunan' => 'SENG'],
            ['kdjenisdindingbangunan' => 7, 'jenisdindingbangunan' => 'LAINNYA'],
        ]);
    }
}
