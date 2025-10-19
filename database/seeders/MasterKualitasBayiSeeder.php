<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKualitasBayiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_kualitasbayi')->insert([
            ['kdkualitasbayi' => 1, 'kualitasbayi' => 'Keguguran kandungan'],
            ['kdkualitasbayi' => 2, 'kualitasbayi' => 'Bayi lahir hidup normal'],
            ['kdkualitasbayi' => 3, 'kualitasbayi' => 'Bayi lahir hidup cacat'],
            ['kdkualitasbayi' => 4, 'kualitasbayi' => 'Bayi lahir mati'],
            ['kdkualitasbayi' => 5, 'kualitasbayi' => 'Bayi lahir berat kurang dari 2,5 kg'],
            ['kdkualitasbayi' => 6, 'kualitasbayi' => 'Bayi lahir berat lebih dari 4 kg'],
            ['kdkualitasbayi' => 7, 'kualitasbayi' => 'Bayi 0-5 tahun hidup yang menderita kelainan organ'],
        ]);
    }
}
