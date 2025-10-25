<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAsetPerikananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('master_asetperikanan')->truncate();

        DB::table('master_asetperikanan')->insert([
            ['kdasetperikanan' => 1, 'asetperikanan' => 'KERAMBA'],
            ['kdasetperikanan' => 2, 'asetperikanan' => 'TAMBAK'],
            ['kdasetperikanan' => 3, 'asetperikanan' => 'JERMAL'],
            ['kdasetperikanan' => 4, 'asetperikanan' => 'PANCING'],
            ['kdasetperikanan' => 5, 'asetperikanan' => 'PUKAT'],
            ['kdasetperikanan' => 6, 'asetperikanan' => 'JALA'],
        ]);

    }
}
