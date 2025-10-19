<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKondisiSumberAirSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_kondisisumberair')->truncate();

        // Insert data master
        DB::table('master_kondisisumberair')->insert([
            ['kdkondisisumberair' => 1, 'kondisisumberair' => 'BAIK'],
            ['kdkondisisumberair' => 2, 'kondisisumberair' => 'BERASA'],
            ['kdkondisisumberair' => 3, 'kondisisumberair' => 'BERWARNA'],
            ['kdkondisisumberair' => 4, 'kondisisumberair' => 'BERBAU'],
        ]);
    }
}
