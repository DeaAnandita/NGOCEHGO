<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterIjasahTerakhirSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('master_ijasahterakhir')->truncate();

        DB::table('master_ijasahterakhir')->insert([
            ['kdijasahterakhir' => 1, 'ijasahterakhir' => 'TIDAK MEMILIKI'],
            ['kdijasahterakhir' => 2, 'ijasahterakhir' => 'SD SEDERAJAT'],
            ['kdijasahterakhir' => 3, 'ijasahterakhir' => 'SMP SEDERAJAT'],
            ['kdijasahterakhir' => 4, 'ijasahterakhir' => 'SMA SEDERAJAT'],
            ['kdijasahterakhir' => 5, 'ijasahterakhir' => 'D1'],
            ['kdijasahterakhir' => 6, 'ijasahterakhir' => 'D2'],
            ['kdijasahterakhir' => 7, 'ijasahterakhir' => 'D3'],
            ['kdijasahterakhir' => 8, 'ijasahterakhir' => 'D4/S1'],
            ['kdijasahterakhir' => 9, 'ijasahterakhir' => 'S2'],
            ['kdijasahterakhir' => 10, 'ijasahterakhir' => 'S3'],
        ]);
    }
}
