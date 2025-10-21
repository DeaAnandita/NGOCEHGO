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
            ['kdasetternak' => 16, 'asetternak' => 'ULAR COBRA'],
            ['kdasetternak' => 17, 'asetternak' => 'BURUNG ONTA'],
            ['kdasetternak' => 18, 'asetternak' => 'ULAR PYTHON'],
            ['kdasetternak' => 19, 'asetternak' => 'BURUNG CENDRAWASIH'],
            ['kdasetternak' => 20, 'asetternak' => 'BURUNG KAKATUA'],
            ['kdasetternak' => 21, 'asetternak' => 'BURUNG BEO'],
            ['kdasetternak' => 22, 'asetternak' => 'BURUNG MERAK'],
            ['kdasetternak' => 23, 'asetternak' => 'BURUNG LANGKA LAINYA'],
            ['kdasetternak' => 24, 'asetternak' => 'BUAYA'],
        ]);
    }
}
