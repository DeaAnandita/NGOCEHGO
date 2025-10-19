<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJenisFisikBangunanSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_jenisfisikbangunan')->truncate();

        // Insert data master
        DB::table('master_jenisfisikbangunan')->insert([
            ['kdjenisfisikbangunan' => 1, 'jenisfisikbangunan' => 'RUMAH TIDAK PANGGUNG'],
            ['kdjenisfisikbangunan' => 2, 'jenisfisikbangunan' => 'RUMAH PANGGUNG'],
            ['kdjenisfisikbangunan' => 3, 'jenisfisikbangunan' => 'RUMAH TERAPUNG'],
        ]);
    }
}
