<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAktaNikahSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_aktanikah')->truncate();

        DB::table('master_aktanikah')->insert([
            ['kdaktanikah' => 1, 'aktanikah' => 'TIDAK MEMILIKI'],
            ['kdaktanikah' => 2, 'aktanikah' => 'YA, DAPAT DITUNJUKKAN'],
            ['kdaktanikah' => 3, 'aktanikah' => 'YA, TIDAK DAPAT DITUNJUKKAN'],
        ]);
    }
}

