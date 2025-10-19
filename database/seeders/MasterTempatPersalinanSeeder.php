<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterTempatPersalinanSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_tempatpersalinan')->truncate();

        DB::table('master_tempatpersalinan')->insert([
            ['kdtempatpersalinan' => 1, 'tempatpersalinan' => 'RS/RB'],
            ['kdtempatpersalinan' => 2, 'tempatpersalinan' => 'PUSKESMAS'],
            ['kdtempatpersalinan' => 3, 'tempatpersalinan' => 'POLINDES'],
            ['kdtempatpersalinan' => 4, 'tempatpersalinan' => 'RUMAH'],
            ['kdtempatpersalinan' => 5, 'tempatpersalinan' => 'LAINNYA'],
        ]);
    }
}
