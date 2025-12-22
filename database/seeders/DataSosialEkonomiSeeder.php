<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSosialEkonomiSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_sosialekonomi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil 15 NIK acak dari data_penduduk
        $nikList = DB::table('data_penduduk')->inRandomOrder()->limit(15)->pluck('nik');

        $records = [];

        foreach ($nikList as $index => $nik) {
            // Kita buat 15 profil yang berbeda-beda tapi sangat realistis
            $profil = $this->getProfilRealistis($index + 1);

            $records[] = [
                'nik'                        => $nik,
                'kdpartisipasisekolah'       => $profil['kdpartisipasisekolah'],
                'kdtingkatsulitdisabilitas'  => $profil['kdtingkatsulitdisabilitas'],
                'kdstatuskedudukankerja'     => $profil['kdstatuskedudukankerja'],
                'kdijasahterakhir'           => $profil['kdijasahterakhir'],
                'kdpenyakitkronis'           => $profil['kdpenyakitkronis'],
                'kdpendapatanperbulan'       => $profil['kdpendapatanperbulan'],
                'kdjenisdisabilitas'         => $profil['kdjenisdisabilitas'],
                'kdlapanganusaha'            => $profil['kdlapanganusaha'],
                'kdimunisasi'                => $profil['kdimunisasi'],
            ];
        }

        DB::table('data_sosialekonomi')->insert($records);
    }

    private function getProfilRealistis($no)
    {
        // 15 kasus realistis yang sering ditemui di desa/kota kecil Indonesia
        $profils = [
            1 => [ // Anak SD masih sekolah
                'kdpartisipasisekolah'      => 1, // SD/SDLB/PAKET A/MI
                'kdtingkatsulitdisabilitas' => 4, // Tidak mengalami kesulitan
                'kdstatuskedudukankerja'    => 9, // Belum/tidak bekerja
                'kdijasahterakhir'          => 1, // Tidak memiliki ijazah
                'kdpenyakitkronis'          => 1, // Tidak ada
                'kdpendapatanperbulan'      => 1, // Tidak berpendapatan
                'kdjenisdisabilitas'        => 12,// Tidak mengalami
                'kdlapanganusaha'           => 22,// Tidak memiliki usaha
                'kdimunisasi'               => 10,// Sudah semua
            ],
            2 => [ // Remaja putus sekolah, buruh lepas
                'kdpartisipasisekolah'      => 6, // Tidak/belum pernah sekolah
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 7, // Pekerja bebas non pertanian
                'kdijasahterakhir'          => 1,
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 2, // ≤ Rp1jt
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 11,// Konstruksi
                'kdimunisasi'               => 10,
            ],
            3 => [ // Ibu rumah tangga, suami petani
                'kdpartisipasisekolah'      => 5, // Tidak bersekolah lagi
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 8, // Pekerja keluarga/tidak dibayar
                'kdijasahterakhir'          => 3, // SMP sederajat
                'kdpenyakitkronis'          => 2, // Hipertensi
                'kdpendapatanperbulan'      => 1, // Tidak berpendapatan sendiri
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 1, // Pertanian padi
                'kdimunisasi'               => 10,
            ],
            4 => [ // Pelajar SMA
                'kdpartisipasisekolah'      => 3, // SMA/SMK
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 9,
                'kdijasahterakhir'          => 3, // SMP (ijazah terakhir sebelum SMA)
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 1,
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 22,
                'kdimunisasi'               => 10,
            ],
            5 => [ // Karyawan swasta lulusan D3
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 4, // Buruh/karyawan swasta
                'kdijasahterakhir'          => 7, // D3
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 4, // Rp1,5-2jt
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 12,// Perdagangan
                'kdimunisasi'               => 10,
            ],
            6 => [ // Petani pemilik lahan
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 1, // Berusaha sendiri
                'kdijasahterakhir'          => 2, // SD
                'kdpenyakitkronis'          => 3, // Rematik
                'kdpendapatanperbulan'      => 3, // Rp1-1,5jt
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 1, // Pertanian padi
                'kdimunisasi'               => 10,
            ],
            7 => [ // Disabilitas fisik, tidak bekerja
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 2, // Banyak kesulitan
                'kdstatuskedudukankerja'    => 9,
                'kdijasahterakhir'          => 4, // SMA
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 1,
                'kdjenisdisabilitas'        => 3, // Berjalan/naik tangga
                'kdlapanganusaha'           => 22,
                'kdimunisasi'               => 10,
            ],
            8 => [ // Mahasiswa S1
                'kdpartisipasisekolah'      => 4, // Perguruan Tinggi
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 9,
                'kdijasahterakhir'          => 4, // SMA (ijazah terakhir)
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 1,
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 22,
                'kdimunisasi'               => 10,
            ],
            9 => [ // Pedagang keliling
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 1, // Berusaha sendiri
                'kdijasahterakhir'          => 3, // SMP
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 3, // Rp1-1,5jt
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 12,// Perdagangan
                'kdimunisasi'               => 10,
            ],
            10 => [ // PNS guru SD
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 5, // PNS/TNI/Polri
                'kdijasahterakhir'          => 8, // S1
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 5, // Rp2-3jt
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 17,// Jasa pendidikan
                'kdimunisasi'               => 10,
            ],
            11 => [ // Lansia, sudah pensiun
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 1, // Sedikit kesulitan
                'kdstatuskedudukankerja'    => 9,
                'kdijasahterakhir'          => 2, // SD
                'kdpenyakitkronis'          => 2, // Hipertensi
                'kdpendapatanperbulan'      => 2, // Dari pensiun/anak
                'kdjenisdisabilitas'        => 1, // Penglihatan
                'kdlapanganusaha'           => 22,
                'kdimunisasi'               => 10,
            ],
            12 => [ // Buruh pabrik
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 4,
                'kdijasahterakhir'          => 4, // SMA/SMK
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 4, // Rp1,5-2jt
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 9, // Industri pengolahan
                'kdimunisasi'               => 10,
            ],
            13 => [ // Pengangguran lulusan SMA
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 9,
                'kdijasahterakhir'          => 4,
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 1,
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 22,
                'kdimunisasi'               => 10,
            ],
            14 => [ // Nelayan
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 1,
                'kdijasahterakhir'          => 2,
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 3,
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 4, // Perikanan tangkap
                'kdimunisasi'               => 10,
            ],
            15 => [ // Driver ojek online
                'kdpartisipasisekolah'      => 5,
                'kdtingkatsulitdisabilitas' => 4,
                'kdstatuskedudukankerja'    => 7, // Pekerja bebas non pertanian
                'kdijasahterakhir'          => 4,
                'kdpenyakitkronis'          => 1,
                'kdpendapatanperbulan'      => 4,
                'kdjenisdisabilitas'        => 12,
                'kdlapanganusaha'           => 14,// Transportasi
                'kdimunisasi'               => 10,
            ],
        ];

        return $profils[$no] ?? $profils[1];
    }
}