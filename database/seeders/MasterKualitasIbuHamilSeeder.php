<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKualitasIbuHamilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_kualitasibuhamil')->insert([
            ['kdkualitasibuhamil' => 1, 'kualitasibuhamil' => 'Ibu hamil periksa di POSYANDU'],
            ['kdkualitasibuhamil' => 2, 'kualitasibuhamil' => 'Ibu hamil periksa di PUSKESMAS'],
            ['kdkualitasibuhamil' => 3, 'kualitasibuhamil' => 'Ibu hamil periksa di rumah sakit'],
            ['kdkualitasibuhamil' => 4, 'kualitasibuhamil' => 'Ibu hamil periksa di dokter praktek'],
            ['kdkualitasibuhamil' => 5, 'kualitasibuhamil' => 'bu hamil periksa di bidan praktek'],
            ['kdkualitasibuhamil' => 6, 'kualitasibuhamil' => 'Ibu hamil periksa di dukun terlatih'],
            ['kdkualitasibuhamil' => 7, 'kualitasibuhamil' => 'Ibu hamil tidak periksa kesehatan'],
            ['kdkualitasibuhamil' => 8, 'kualitasibuhamil' => 'Ibu hamil yang meninggal'],
            ['kdkualitasibuhamil' => 9, 'kualitasibuhamil' => 'Ibu hamil melahirkan'],
            ['kdkualitasibuhamil' => 10, 'kualitasibuhamil' => 'Ibu nifas sakit'],
            ['kdkualitasibuhamil' => 11, 'kualitasibuhamil' => 'Kematian ibu nifas'],
            ['kdkualitasibuhamil' => 12, 'kualitasibuhamil' => 'Ibu nifas sehat'],
            ['kdkualitasibuhamil' => 13, 'kualitasibuhamil' => 'Kematian ibu saat melahirkan'],
        ]);
    }
}
