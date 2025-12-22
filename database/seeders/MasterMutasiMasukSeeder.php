<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterMutasiMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('master_mutasimasuk')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('master_mutasimasuk')->insert([
            ['kdmutasimasuk' => 1, 'mutasimasuk' => 'MUTASI TETAP'],
            ['kdmutasimasuk' => 2, 'mutasimasuk' => 'MUTASI LAHIR'],
            ['kdmutasimasuk' => 3, 'mutasimasuk' => 'MUTASI DATANG'],
            ['kdmutasimasuk' => 4, 'mutasimasuk' => 'MUTASI KELUAR'],
        ]);
    }
}
