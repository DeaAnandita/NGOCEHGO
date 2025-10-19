<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSumberDayaTerpasangSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_sumberdayaterpasang')->truncate();

        // Insert data master
        DB::table('master_sumberdayaterpasang')->insert([
            ['kdsumberdayaterpasang' => 1, 'sumberdayaterpasang' => '450 WATT'],
            ['kdsumberdayaterpasang' => 2, 'sumberdayaterpasang' => '900 WATT'],
            ['kdsumberdayaterpasang' => 3, 'sumberdayaterpasang' => '1.300 WATT'],
            ['kdsumberdayaterpasang' => 4, 'sumberdayaterpasang' => '2.200 WATT'],
            ['kdsumberdayaterpasang' => 5, 'sumberdayaterpasang' => 'LEBIH DARI 2.200 WATT'],
            ['kdsumberdayaterpasang' => 6, 'sumberdayaterpasang' => 'TANPA METERAN'],
        ]);
    }
}