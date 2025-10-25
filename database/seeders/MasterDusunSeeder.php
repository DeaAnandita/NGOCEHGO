<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDusunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_dusun')->insert([
            ['kddusun' => 1, 'dusun' => 'WINONG'],
            ['kddusun' => 2, 'dusun' => 'KRAJAN'],
            ['kddusun' => 3, 'dusun' => 'PLOSO'],
            ['kddusun' => 4, 'dusun' => 'GONDANG'],
            ['kddusun' => 5, 'dusun' => 'NGEMPLAK'],
        ]);
    }
}
