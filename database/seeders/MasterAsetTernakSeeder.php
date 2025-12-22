<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAsetTernakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('master_asetternak')->truncate();

        DB::table('master_asetternak')->insert([
            ['kdasetternak' => 1, 'asetternak' => 'SAPI'],
            ['kdasetternak' => 2, 'asetternak' => 'KERBAU'],
            ['kdasetternak' => 3, 'asetternak' => 'BABI'],
            ['kdasetternak' => 4, 'asetternak' => 'AYAM KAMPUNG'],
            ['kdasetternak' => 5, 'asetternak' => 'AYAM BROILER'],
            ['kdasetternak' => 6, 'asetternak' => 'BEBEK'],
            ['kdasetternak' => 7, 'asetternak' => 'KUDA'],
            ['kdasetternak' => 8, 'asetternak' => 'KAMBING'],
            ['kdasetternak' => 9, 'asetternak' => 'DOMBA'],
            ['kdasetternak' => 10,'asetternak' => 'ANGSA'],
            ['kdasetternak' => 11, 'asetternak' => 'BURUNG PUYUH'],
            ['kdasetternak' => 12, 'asetternak' => 'KELINCI'],
            ['kdasetternak' => 13, 'asetternak' => 'BURUNG WALET'],
            ['kdasetternak' => 14, 'asetternak' => 'ANJING'],
            ['kdasetternak' => 15, 'asetternak' => 'KUCING'],
            
        ]);
    }
}
