<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataUsahaArtSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_usahaart')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil 15 NIK dari tabel data_penduduk
        $nikList = DB::table('data_penduduk')->limit(15)->pluck('nik');

        $usahaList = [
            'Warung Sari Rasa',
            'Toko Berkah Jaya',
            'Laundry Bersih Kilat',
            'Kedai Kopi Nusantara',
            'CV Fadhila Gorden',
            'Apiku Stove Craft',
            'Warung Makan Bu Tini',
            'Butik Anindya',
            'Bengkel Motor Jaya',
            'Toko Elektronik Abadi',
            'Studio Foto Kenangan',
            'Percetakan Citra Print',
            'Usaha Roti Harum',
            'Kios Sembako Laris',
            'Warung Sate Maknyus'
        ];

        $records = [];
        $i = 0;

        foreach ($nikList as $nik) {
            $records[] = [
                'nik' => $nik,
                'kdlapanganusaha' => fake()->numberBetween(1, 21), // contoh: jasa, industri, perdagangan
                'kdtempatusaha' => fake()->numberBetween(1, 2),   // rumah sendiri, sewa, kios
                'kdomsetusaha' => fake()->numberBetween(1, 4),    // <1 jt, 1–3 jt, 3–5 jt, >5 jt
                'usahaart_jumlahpekerja' => fake()->numberBetween(1, 8),
                'usahaart_namausaha' => $usahaList[$i] ?? fake()->company(),
            ];
            $i++;
        }

        DB::table('data_usahaart')->insert($records);
    }
}
