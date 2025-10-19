<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterCaraPerolehanAirSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_caraperolehanair')->truncate();

        // Insert data master
        DB::table('master_caraperolehanair')->insert([
            ['kdcaraperolehanair' => 1, 'caraperolehanair' => 'MEMBELI ECERAN'],
            ['kdcaraperolehanair' => 2, 'caraperolehanair' => 'LANGGANAN'],
            ['kdcaraperolehanair' => 3, 'caraperolehanair' => 'TIDAK MEMBELI'],
        ]);
    }
}
