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

        $nikList = DB::table('data_penduduk')->inRandomOrder()->limit(15)->pluck('nik');

        $records = [];

        foreach ($nikList as $index => $nik) {
            $profil = $this->getProfilUsaha($index + 1);

            $records[] = [
                'nik'                    => $nik,
                'kdlapanganusaha'        => $profil['kdlapanganusaha'],
                'kdtempatusaha'          => $profil['kdtempatusaha'],
                'kdomsetusaha'           => $profil['kdomsetusaha'],
                'usahaart_jumlahpekerja' => $profil['jumlahpekerja'],
                'usahaart_namausaha'     => $profil['namausaha'],
            ];
        }

        DB::table('data_usahaart')->insert($records);
    }

    private function getProfilUsaha($no)
    {
        // 15 usaha dengan PETERNAKAN (kode 6) sebagai sektor paling dominan (muncul lebih sering)
        $profils = [

            1 => [
                'namausaha'      => 'Peternakan Ayam Petelur Makmur',
                'kdlapanganusaha'=> 6,  // Peternakan
                'kdtempatusaha'  => 2,  // Punya tempat tetap
                'kdomsetusaha'   => 3,
                'jumlahpekerja'  => 4,
            ],

            2 => [
                'namausaha'      => 'Kandang Sapi Perah Jaya',
                'kdlapanganusaha'=> 6,  // Peternakan
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 4,
                'jumlahpekerja'  => 5,
            ],

            3 => [
                'namausaha'      => 'Peternakan Kambing Domba Sejahtera',
                'kdlapanganusaha'=> 6,  // Peternakan
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 3,
                'jumlahpekerja'  => 3,
            ],

            4 => [
                'namausaha'       => 'Peternakan Ayam Potong Barokah',
                'kdlapanganusaha'=> 6,  // Peternakan
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 3,
                'jumlahpekerja'  => 6,
            ],

            5 => [
                'namausaha'      => 'Ternak Bebek Pedaging Lestari',
                'kdlapanganusaha'=> 6,  // Peternakan
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 2,
                'jumlahpekerja'  => 3,
            ],

            6 => [
                'namausaha'      => 'Peternakan Ayam Kampung Mandiri',
                'kdlapanganusaha'=> 6,  // Peternakan
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 2,
                'jumlahpekerja'  => 2,
            ],

            7 => [
                'namausaha'      => 'Kandang Babi Modern',
                'kdlapanganusaha'=> 6,  // Peternakan (disesuaikan konteks lokal jika perlu)
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 4,
                'jumlahpekerja'  => 4,
            ],

            8 => [
                'namausaha'      => 'Usaha Tani Padi Makmur',
                'kdlapanganusaha'=> 1,  // Pertanian tanaman padi & palawija
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 3,
                'jumlahpekerja'  => 3,
            ],

            9 => [
                'namausaha'      => 'Kebun Sayur Hidroponik Segar',
                'kdlapanganusaha'=> 2,  // Hortikultura
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 2,
                'jumlahpekerja'  => 2,
            ],

            10 => [
                'namausaha'      => 'Nelayan Laut Harapan',
                'kdlapanganusaha'=> 4,  // Perikanan tangkap
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 3,
                'jumlahpekerja'  => 3,
            ],

            11 => [
                'namausaha'      => 'Kolam Ikan Nila Jaya',
                'kdlapanganusaha'=> 5,  // Perikanan budidaya
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 3,
                'jumlahpekerja'  => 2,
            ],

            12 => [
                'namausaha'      => 'Toko Sembako Berkah',
                'kdlapanganusaha'=> 12, // Perdagangan
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 3,
                'jumlahpekerja'  => 2,
            ],

            13 => [
                'namausaha'      => 'Warung Makan Sederhana',
                'kdlapanganusaha'=> 13, // Hotel & rumah makan
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 2,
                'jumlahpekerja'  => 3,
            ],

            14 => [
                'namausaha'      => 'Jasa Angkut Barang Desa',
                'kdlapanganusaha'=> 14, // Transportasi & pergudangan
                'kdtempatusaha'  => 1,  // Tidak punya tempat tetap
                'kdomsetusaha'   => 3,
                'jumlahpekerja'  => 1,
            ],

            15 => [
                'namausaha'      => 'Bakery Rumahan Lezat',
                'kdlapanganusaha'=> 9,  // Industri pengolahan
                'kdtempatusaha'  => 2,
                'kdomsetusaha'   => 2,
                'jumlahpekerja'  => 3,
            ],
        ];

        return $profils[$no] ?? $profils[1];
    }
}