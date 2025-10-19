<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKartuIdentitasSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_kartuidentitas')->truncate();

        DB::table('master_kartuidentitas')->insert([
            ['kdkartuidentitas' => 1, 'kartuidentitas' => 'TIDAK MEMILIKI'],
            ['kdkartuidentitas' => 2, 'kartuidentitas' => 'KTP'],
            ['kdkartuidentitas' => 3, 'kartuidentitas' => 'SIM'],
            ['kdkartuidentitas' => 4, 'kartuidentitas' => 'KARTU PELAJAR'],
            ['kdkartuidentitas' => 5, 'kartuidentitas' => 'AKTA KELAHIRAN'],
            ['kdkartuidentitas' => 6, 'kartuidentitas' => 'PASPORT'],
            ['kdkartuidentitas' => 7, 'kartuidentitas' => 'LAINNYA'],
        ]);
    }
}

