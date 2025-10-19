<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterStatusKawinSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_statuskawin')->truncate();

        DB::table('master_statuskawin')->insert([
            ['kdstatuskawin' => 1, 'statuskawin' => 'BELUM KAWIN'],
            ['kdstatuskawin' => 2, 'statuskawin' => 'KAWIN/MENIKAH'],
            ['kdstatuskawin' => 3, 'statuskawin' => 'CERAI HIDUP'],
            ['kdstatuskawin' => 4, 'statuskawin' => 'CERAI MATI'],
            ['kdstatuskawin' => 5, 'statuskawin' => 'HIDUP BERSAMA'],
        ]);
    }
}

