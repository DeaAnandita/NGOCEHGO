<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataPendudukSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_keluarga')->truncate();
        DB::table('data_penduduk')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $namaPria = ['Budi', 'Agus', 'Rudi', 'Teguh', 'Hendra', 'Rizki', 'Andi', 'Rangga', 'Taufik', 'Wawan', 'Anton', 'Heri', 'Bambang', 'Darmawan', 'Fajar', 'Eko', 'Bayu', 'Rafli', 'Agung', 'Ridwan'];
        $namaWanita = ['Siti', 'Rina', 'Dewi', 'Nur', 'Lina', 'Rizka', 'Ayu', 'Mira', 'Fitri', 'Rani', 'Desi', 'Dian', 'Nina', 'Ratna', 'Lilis', 'Kartini', 'Rika', 'Maya', 'Andini'];
        $namaBelakang = ['Santoso', 'Wijaya', 'Hartono', 'Gunawan', 'Pratama', 'Saputra', 'Lestari', 'Anggraini', 'Ramadhani', 'Permata', 'Putri', 'Hidayah', 'Susanto', 'Firmansyah', 'Wahyuni', 'Nugroho'];

        $dusunList = [1 => 'Winong', 2 => 'Krajan', 3 => 'Ploso', 4 => 'Gondang', 5 => 'Ngemplak'];
        $dataKeluarga = [];
        $dataPenduduk = [];
        $nikCounter = 100000;

        for ($i = 1; $i <= 50; $i++) {
            $dusun = array_rand($dusunList);
            $rw = str_pad(rand(1, 5), 3, '0', STR_PAD_LEFT);
            $rt = str_pad(rand(1, 5), 3, '0', STR_PAD_LEFT);
            $noKK = '33741234' . str_pad($i, 8, '0', STR_PAD_LEFT);

            // Jenis mutasi: 1=Normal, 2=Datang dalam kabupaten, 3=Datang luar kabupaten
            $mutasi = rand(1, 3);

            // Wilayah datang (kalau mutasi datang)
            $kdprovinsi = $mutasi == 3 ? rand(1, 5) : null;
            $kdkabupaten = $mutasi == 3 ? rand(1, 7) : null;
            $kdkecamatan = $mutasi == 3 ? rand(1, 16) : null;
            $kddesa = $mutasi == 3 ? rand(1, 135) : null;

            // Kepala keluarga laki-laki atau perempuan
            $kkLaki = rand(1, 100) <= 75;

            $namaKK = $kkLaki
                ? $namaPria[array_rand($namaPria)] . ' ' . $namaBelakang[array_rand($namaBelakang)]
                : $namaWanita[array_rand($namaWanita)] . ' ' . $namaBelakang[array_rand($namaBelakang)];

            $dataKeluarga[] = [
                'no_kk' => $noKK,
                'kdmutasimasuk' => $mutasi,
                'keluarga_tanggalmutasi' => now(),
                'keluarga_kepalakeluarga' => $namaKK,
                'kddusun' => $dusun,
                'keluarga_rw' => $rw,
                'keluarga_rt' => $rt,
                'keluarga_alamatlengkap' => 'Jl. ' . Str::title(fake()->streetName()) . ' No. ' . rand(1, 50) . ', Dusun ' . $dusunList[$dusun],
                'kdprovinsi' => $kdprovinsi,
                'kdkabupaten' => $kdkabupaten,
                'kdkecamatan' => $kdkecamatan,
                'kddesa' => $kddesa,
            ];

            // ====== Penduduk (sinkron) ======
            $nourut = 1;

            // Kepala Keluarga
            $nikCounter++;
            $dataPenduduk[] = [
                'nik' => '33741234' . str_pad($nikCounter, 8, '0', STR_PAD_LEFT),
                'no_kk' => $noKK,
                'kdmutasimasuk' => $mutasi,
                'penduduk_nourutkk' => '01',
                'penduduk_namalengkap' => $namaKK,
                'penduduk_tempatlahir' => 'Kudus',
                'penduduk_noaktalahir' => 'AKL-' . date('Y') . rand(10000,99999),
                'penduduk_tanggallahir' => now()->subYears(rand(30, 60))->format('Y-m-d'),
                'kdjeniskelamin' => $kkLaki ? 1 : 2,
                'kdhubungankeluarga' => 1,
                'kdagama' => 1,
                'kdstatuskawin' => 2,
                'kdpekerjaan' => rand(1, 8),
                'penduduk_namaayah' => $namaPria[array_rand($namaPria)] . ' ' . $namaBelakang[array_rand($namaBelakang)],
                'penduduk_namaibu' => $namaWanita[array_rand($namaWanita)] . ' ' . $namaBelakang[array_rand($namaBelakang)],
                'penduduk_goldarah' => ['A', 'B', 'AB', 'O'][array_rand(['A', 'B', 'AB', 'O'])],
                'penduduk_kewarganegaraan' => 'INDONESIA',
                'penduduk_tanggalmutasi' => now(),
            ];

            // Kalau kepala keluarga laki-laki → tambah istri
            if ($kkLaki) {
                $nikCounter++;
                $namaIstri = $namaWanita[array_rand($namaWanita)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
                $nourut++;
                $dataPenduduk[] = [
                    'nik' => '33741234' . str_pad($nikCounter, 8, '0', STR_PAD_LEFT),
                    'no_kk' => $noKK,
                    'kdmutasimasuk' => $mutasi,
                    'penduduk_nourutkk' => str_pad($nourut, 2, '0', STR_PAD_LEFT),
                    'penduduk_namalengkap' => $namaIstri,
                    'penduduk_tempatlahir' => 'Kudus',
                    'penduduk_noaktalahir' => 'AKL-' . date('Y') . rand(10000,99999),
                    'penduduk_tanggallahir' => now()->subYears(rand(25, 55))->format('Y-m-d'),
                    'kdjeniskelamin' => 2,
                    'kdhubungankeluarga' => 2,
                    'kdagama' => 1,
                    'kdstatuskawin' => 2,
                    'kdpekerjaan' => rand(3, 8),
                    'penduduk_namaayah' => $namaPria[array_rand($namaPria)] . ' ' . $namaBelakang[array_rand($namaBelakang)],
                    'penduduk_namaibu' => $namaWanita[array_rand($namaWanita)] . ' ' . $namaBelakang[array_rand($namaBelakang)],
                    'penduduk_goldarah' => ['A', 'B', 'AB', 'O'][array_rand(['A', 'B', 'AB', 'O'])],
                    'penduduk_kewarganegaraan' => 'INDONESIA',
                    'penduduk_tanggalmutasi' => now(),
                ];
            }

            // Tambah anak-anak (1–3 anak)
            $jumlahAnak = rand(1, 3);
            for ($a = 1; $a <= $jumlahAnak; $a++) {
                $nikCounter++;
                $genderAnak = rand(0, 1) ? 1 : 2;
                $namaAnak = ($genderAnak == 1 ? $namaPria[array_rand($namaPria)] : $namaWanita[array_rand($namaWanita)]) . ' ' . $namaBelakang[array_rand($namaBelakang)];
                $nourut++;

                $dataPenduduk[] = [
                    'nik' => '33741234' . str_pad($nikCounter, 8, '0', STR_PAD_LEFT),
                    'no_kk' => $noKK,
                    'kdmutasimasuk' => $mutasi,
                    'penduduk_nourutkk' => str_pad($nourut, 2, '0', STR_PAD_LEFT),
                    'penduduk_namalengkap' => $namaAnak,
                    'penduduk_tempatlahir' => 'Kudus',
                    'penduduk_noaktalahir' => 'AKL-' . date('Y') . rand(10000,99999),
                    'penduduk_tanggallahir' => now()->subYears(rand(5, 25))->format('Y-m-d'),
                    'kdjeniskelamin' => $genderAnak,
                    'kdhubungankeluarga' => 3,
                    'kdagama' => 1,
                    'kdstatuskawin' => 1,
                    'kdpekerjaan' => rand(7, 10),
                    'penduduk_namaayah' => $namaKK,
                    'penduduk_namaibu' => $kkLaki ? $namaIstri ?? '' : $namaKK,
                    'penduduk_goldarah' => ['A', 'B', 'AB', 'O'][array_rand(['A', 'B', 'AB', 'O'])],
                    'penduduk_kewarganegaraan' => 'INDONESIA',
                    'penduduk_tanggalmutasi' => now(),
                ];
            }
        }

        DB::table('data_keluarga')->insert($dataKeluarga);
        DB::table('data_penduduk')->insert($dataPenduduk);
    }
}
