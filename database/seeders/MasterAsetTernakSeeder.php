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
            ['kdasetternak' => 1, 'asetternak' => '1 SAPI'],
            ['kdasetternak' => 2, 'asetternak' => '2 KERBAU'],
            ['kdasetternak' => 3, 'asetternak' => '3 BABI'],
            ['kdasetternak' => 4, 'asetternak' => '4 AYAM KAMPUNG'],
            ['kdasetternak' => 5, 'asetternak' => '5 AYAM BROILER'],
            ['kdasetternak' => 6, 'asetternak' => '6 BEBEK'],
            ['kdasetternak' => 7, 'asetternak' => '7 KUDA'],
            ['kdasetternak' => 8, 'asetternak' => '8 KAMBING'],
            ['kdasetternak' => 9, 'asetternak' => '9 DOMBA'],
            ['kdasetternak' => 10,'asetternak' => '10 ANGSA'],
            ['kdasetternak' => 11, 'asetternak' => '11 BURUNG PUYUH'],
            ['kdasetternak' => 12, 'asetternak' => '12 KELINCI'],
            ['kdasetternak' => 13, 'asetternak' => '13 BURUNG WALET'],
            ['kdasetternak' => 14, 'asetternak' => '14 ANJING'],
            ['kdasetternak' => 15, 'asetternak' => '15 KUCING'],
            ['kdasetternak' => 16, 'asetternak' => '16 ULAR COBRA'],
            ['kdasetternak' => 17, 'asetternak' => '17 BURUNG ONTA'],
            ['kdasetternak' => 18, 'asetternak' => '18 ULAR PYTHON'],
            ['kdasetternak' => 19, 'asetternak' => '19 BURUNG CENDRAWASIH'],
            ['kdasetternak' => 20, 'asetternak' => '20 BURUNG KAKATUA'],
            ['kdasetternak' => 21, 'asetternak' => '21 BURUNG BEO'],
            ['kdasetternak' => 22, 'asetternak' => '22 BURUNG MERAK'],
            ['kdasetternak' => 23, 'asetternak' => '23 BURUNG LANGKA LAINYA'],
            ['kdasetternak' => 24, 'asetternak' => '24 BUAYA'],
        ]);
    }
}
