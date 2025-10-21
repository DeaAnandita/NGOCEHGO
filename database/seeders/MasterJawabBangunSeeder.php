<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabBangunSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_jawabbangun')->truncate();

        DB::table('master_jawabbangun')->insert([
             
            ['kdjawabbangun' => 0, 'jawabbangun' => 'TIDAK DIISI'],
            ['kdjawabbangun' => 1, 'jawabbangun' => 'YA'],
            ['kdjawabbangun' => 2, 'jawabbangun' => 'TIDAK'],
	        ['kdjawabbangun' => 3, 'jawabbangun' => 'TIDAK BERLAKU'],
           
        ]);
    }
}