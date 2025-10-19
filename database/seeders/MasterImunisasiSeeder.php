<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterImunisasiSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_imunisasi')->truncate();

        DB::table('master_imunisasi')->insert([
            ['kdimunisasi' => 1, 'imunisasi' => 'DPT-1'],
            ['kdimunisasi' => 2, 'imunisasi' => 'BCG'],
            ['kdimunisasi' => 3, 'imunisasi' => 'POLIO-1'],
            ['kdimunisasi' => 4, 'imunisasi' => 'DPT-2'],
            ['kdimunisasi' => 5, 'imunisasi' => 'POLIO-2'],
            ['kdimunisasi' => 6, 'imunisasi' => 'POLIO-3'],
            ['kdimunisasi' => 7, 'imunisasi' => 'DPT-3'],
            ['kdimunisasi' => 8, 'imunisasi' => 'CAMPAK'],
            ['kdimunisasi' => 9, 'imunisasi' => 'CACAR'],
            ['kdimunisasi' => 10, 'imunisasi' => 'SUDAH SEMUA'],
        ]);
    }
}
