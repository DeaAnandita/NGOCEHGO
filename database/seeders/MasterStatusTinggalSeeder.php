<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterStatusTinggalSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_statustinggal')->truncate();

        DB::table('master_statustinggal')->insert([
            ['kdstatustinggal' => 1, 'statustinggal' => 'TIDAK TINGGAL BERSAMA, BERADA DILUAR KOTA'],
            ['kdstatustinggal' => 2, 'statustinggal' => 'TIDAK TINGGAL BERSAMA, BERADA DIDALAM KOTA'],
            ['kdstatustinggal' => 3, 'statustinggal' => 'TINGGAL BERSAMA'],
        ]);
    }
}

