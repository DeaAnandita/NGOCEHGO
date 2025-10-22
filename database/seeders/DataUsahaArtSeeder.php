<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataUsahaArtSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key sementara agar tidak error saat truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_usahaart')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('data_usahaart')->insert([
            [
                'nik' => '3374123400000001', // FK ke data_penduduk
                'kdlapanganusaha' => 2,      // misal: Industri makanan ringan
                'kdtempatusaha' => 1,        // misal: Rumah sendiri
                'kdomsetusaha' => 3,         // misal: 3–5 juta per bulan
                'usahaart_jumlahpekerja' => 4,
                'usahaart_namausaha' => 'Apiku Stove Craft'
            ],
            [
                'nik' => '3374123400000002',
                'kdlapanganusaha' => 3,      // misal: Perdagangan eceran
                'kdtempatusaha' => 2,        // misal: Toko sewa
                'kdomsetusaha' => 2,         // misal: 1–3 juta per bulan
                'usahaart_jumlahpekerja' => 2,
                'usahaart_namausaha' => 'Warung Siti'
            ],
            [
                'nik' => '3374123400000003',
                'kdlapanganusaha' => 1,      // misal: Jasa
                'kdtempatusaha' => 1,        // misal: Online / Rumah + Daring
                'kdomsetusaha' => 4,         // misal: >5 juta per bulan
                'usahaart_jumlahpekerja' => 5,
                'usahaart_namausaha' => 'CV Fadhila Gorden'
            ],
        ]);
    }
}
