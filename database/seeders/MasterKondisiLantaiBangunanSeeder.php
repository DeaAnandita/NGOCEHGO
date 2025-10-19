<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKondisiLantaiBangunanSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang
        //DB::table('master_kondisilantaibangunan')->truncate();

        // Insert data master
        DB::table('master_kondisilantaibangunan')->insert([
            ['kdkondisilantaibangunan' => 1, 'kondisilantaibangunan' => 'BAGUS/KUALITAS TINGGI'],
            ['kdkondisilantaibangunan' => 2, 'kondisilantaibangunan' => 'JELEK/KUALITAS RENDAH'],
        ]);
    }
}
