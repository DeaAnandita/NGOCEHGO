<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPertolonganPersalinanSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_pertolonganpersalinan')->truncate();

        DB::table('master_pertolonganpersalinan')->insert([
            ['kdpertolonganpersalinan' => 1, 'pertolonganpersalinan' => 'DOKTER'],
            ['kdpertolonganpersalinan' => 2, 'pertolonganpersalinan' => 'BIDAN/PERAWAT'],
            ['kdpertolonganpersalinan' => 3, 'pertolonganpersalinan' => 'DUKUN'],
            ['kdpertolonganpersalinan' => 4, 'pertolonganpersalinan' => 'LAINNYA'],
        ]);
}
}
