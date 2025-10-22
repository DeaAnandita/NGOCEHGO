<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSosialEkonomiSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key sementara agar tidak error saat truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_sosialekonomi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('data_sosialekonomi')->insert([
            [
                'nik' => '3374123400000001', // FK ke data_penduduk
                'kdpartisipasisekolah' => 1,         // misal: Masih sekolah
                'kdtingkatsulitdisabilitas' => 1,    // misal: Tidak ada kesulitan
                'kdstatuskedudukankerja' => 2,       // misal: Pekerja tetap
                'kdijasahterakhir' => 4,             // misal: SMA/SMK
                'kdpenyakitkronis' => 1,             // misal: Tidak memiliki penyakit kronis
                'kdpendapatanperbulan' => 3,         // misal: 3 juta - 5 juta
                'kdjenisdisabilitas' => 2,        // Tidak ada disabilitas
                'kdlapanganusaha' => 2,              // misal: Industri manufaktur
                'kdimunisasi' => 1,                  // misal: Lengkap
            ],
            [
                'nik' => '3374123400000002',
                'kdpartisipasisekolah' => 2,         // misal: Tidak sekolah
                'kdtingkatsulitdisabilitas' => 2,    // misal: Sedang
                'kdstatuskedudukankerja' => 3,       // misal: Ibu rumah tangga
                'kdijasahterakhir' => 3,             // misal: SMP
                'kdpenyakitkronis' => 2,             // misal: Hipertensi
                'kdpendapatanperbulan' => 2,         // misal: 1 juta - 3 juta
                'kdjenisdisabilitas' => 1,
                'kdlapanganusaha' => 3,              // misal: Perdagangan
                'kdimunisasi' => 1,                  // misal: Lengkap
            ],
            [
                'nik' => '3374123400000003',
                'kdpartisipasisekolah' => 1,         // misal: Masih sekolah
                'kdtingkatsulitdisabilitas' => 1,
                'kdstatuskedudukankerja' => 1,       // misal: Pekerja lepas
                'kdijasahterakhir' => 5,             // misal: Diploma/Sarjana
                'kdpenyakitkronis' => 1,
                'kdpendapatanperbulan' => 4,         // misal: >5 juta
                'kdjenisdisabilitas' => 2,
                'kdlapanganusaha' => 1,              // misal: Jasa
                'kdimunisasi' => 1,
            ],
        ]);
    }
}
