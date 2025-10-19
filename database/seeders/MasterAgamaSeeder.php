<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAgamaSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_agama')->truncate();

        DB::table('master_agama')->insert([
            ['kdagama' => 1, 'agama' => 'ISLAM'],
            ['kdagama' => 2, 'agama' => 'KRISTEN PROTESTAN'],
            ['kdagama' => 3, 'agama' => 'KRISTEN KATHOLIK'],
            ['kdagama' => 4, 'agama' => 'HINDU'],
            ['kdagama' => 5, 'agama' => 'BUDHA'],
            ['kdagama' => 6, 'agama' => 'KONGHUCHU'],
            ['kdagama' => 7, 'agama' => 'LAINNYA'],
        ]);
    }
}
