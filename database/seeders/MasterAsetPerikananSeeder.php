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
            ['kdasetperikanan' => 1, 'asetperikanan' => '1 KERAMBA'],
            ['kdasetperikanan' => 2, 'asetperikanan' => '2 TAMBAK'],
            ['kdasetperikanan' => 3, 'asetperikanan' => '3 JERMAL'],
            ['kdasetperikanan' => 4, 'asetperikanan' => '4 PANCING'],
            ['kdasetperikanan' => 5, 'asetperikanan' => '5 PUKAT'],
            ['kdasetperikanan' => 6, 'asetperikanan' => '6 JALA'],
        ]);

    }
}
