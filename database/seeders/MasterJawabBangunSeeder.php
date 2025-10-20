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
             
            ['kdjawabbangun' => 1, 'jawabbangun' => 'TIDAK DIISI'],
            ['kdjawabbangun' => 3, 'jawabbangun' => 'YA'],
            ['kdjawabbangun' => 4, 'jawabbangun' => 'TIDAK'],
	        ['kdjawabbangun' => 5, 'jawabbangun' => 'TIDAK BERLAKU'],
           
        ]);
    }
}