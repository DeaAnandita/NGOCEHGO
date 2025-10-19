<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterTempatUsahaSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_tempatusaha')->truncate();

        DB::table('master_tempatusaha')->insert([
            ['kdtempatusaha' => 1, 'tempatusaha' => 'TIDAK ADA'],
            ['kdtempatusaha' => 2, 'tempatusaha' => 'ADA'],
        ]);
    }
}

