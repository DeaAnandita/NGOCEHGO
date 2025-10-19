<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSarprasKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('master_sarpraskerja')->truncate();

        DB::table('master_sarpraskerja')->insert([
            ['kdsarpraskerja' => 1, 'sarpraskerja' => 'JARING UDANG'],
            ['kdsarpraskerja' => 2, 'sarpraskerja' => 'KERAMBA JARING APUNG HDPE 4 LOBANG/ PETAK'],
            ['kdsarpraskerja' => 3, 'sarpraskerja' => 'SEPEDA MOTOR/OJEK MOTOR/BENTOR'],
            ['kdsarpraskerja' => 4, 'sarpraskerja' => 'MOBIL'],
            ['kdsarpraskerja' => 5, 'sarpraskerja' => 'MESIN KALIBRASI'],
            ['kdsarpraskerja' => 6, 'sarpraskerja' => 'GROBAK'],
            ['kdsarpraskerja' => 7, 'sarpraskerja' => 'TOKO FASHION'],
            ['kdsarpraskerja' => 8, 'sarpraskerja' => 'JALA IKAN'],
            ['kdsarpraskerja' => 9, 'sarpraskerja' => 'BUBU'],
            ['kdsarpraskerja' => 10, 'sarpraskerja' => 'ALAT PENUNJANG KULINER'],
            ['kdsarpraskerja' => 11, 'sarpraskerja' => 'KOMPRESOR'],
            ['kdsarpraskerja' => 12, 'sarpraskerja' => 'MESIN BELAH'],
            ['kdsarpraskerja' => 13, 'sarpraskerja' => 'ALAT BANGUNAN'],
            ['kdsarpraskerja' => 14, 'sarpraskerja' => 'ETALASE'],
            ['kdsarpraskerja' => 15, 'sarpraskerja' => 'ALAT PENUNJANG MEUBLE/MEUBLER'],
            ['kdsarpraskerja' => 16, 'sarpraskerja' => 'ALAT PENUNJANG BENGKEL LAS'],
            ['kdsarpraskerja' => 17, 'sarpraskerja' => 'ALAT PENUNJANG BENGKEL MOTOR/MOBIL/KAPAL MESIN'],
            ['kdsarpraskerja' => 18, 'sarpraskerja' => 'ALAT PENUNJANG KONVEKSI'],
            ['kdsarpraskerja' => 19, 'sarpraskerja' => 'ALAT PENUNJANG RIAS/SALON'],
            ['kdsarpraskerja' => 20, 'sarpraskerja' => 'ALAT PENUNJANG PERCETAKAN'],
            ['kdsarpraskerja' => 21, 'sarpraskerja' => 'ALAT PENUNJANG PEDAGANG KAKI LIMA/ KELILING/PASAR'],
            ['kdsarpraskerja' => 22, 'sarpraskerja' => 'ALAT AIR ISI ULANG'],
            ['kdsarpraskerja' => 23, 'sarpraskerja' => 'ALAT PENUNJANG KESEHATAN/ TERAPHY'],
            ['kdsarpraskerja' => 24, 'sarpraskerja' => 'ALAT PENUNJANG PEKERJAAN PROYEK KONTRUKSI/BARANG'],
            ['kdsarpraskerja' => 25, 'sarpraskerja' => 'BECAK'],
        ]);
    }
}
