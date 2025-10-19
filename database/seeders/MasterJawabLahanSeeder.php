<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterJawabLahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_jawablahan')->truncate();

        DB::table('master_jawablahan')->insert([
            ['kdjawablahan' => 0, 'jawablahan' => 'TIDAK MEMILIKI'],
            ['kdjawablahan' => 1, 'jawablahan' => 'MEMILIKI ANTARA 0,1-0,2 HA'],
            ['kdjawablahan' => 2, 'jawablahan' => 'MEMILIKI ANTARA 0,21-0,3 HA'],
            ['kdjawablahan' => 3, 'jawablahan' => 'MEMILIKI ANTARA 0,31-0,4 HA'],
            ['kdjawablahan' => 4, 'jawablahan' => 'MEMILIKI ANTARA 0,41-0,5 HA'],
            ['kdjawablahan' => 5, 'jawablahan' => 'MEMILIKI ANTARA 0,51-0,6 HA'],
            ['kdjawablahan' => 6, 'jawablahan' => 'MEMILIKI ANTARA 0,61-0,7 HA'],
            ['kdjawablahan' => 7, 'jawablahan' => 'MEMILIKI ANTARA 0,71-0,8 HA'],
            ['kdjawablahan' => 8, 'jawablahan' => 'MEMILIKI ANTARA 0,81-0,9 HA'],
            ['kdjawablahan' => 9, 'jawablahan' => 'MEMILIKI ANTARA 0,91-1,0 HA'],
            ['kdjawablahan' => 10, 'jawablahan' => 'MEMILIKI ANTARA 1,0 - 5,0 HA'],
            ['kdjawablahan' => 11, 'jawablahan' => 'MEMILIKI LEBIH DARI 5,0 HA'],
        ]);
    }
}
