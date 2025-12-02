<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPendudukSeeder extends Seeder
{
    protected $namaPria = ['Ahmad', 'Budi', 'Candra', 'Dedi', 'Eko', 'Fajar', 'Galih', 'Hadi', 'Irfan', 'Joko', 'Kurniawan', 'Lukman', 'Maman', 'Nico', 'Oka', 'Putra', 'Qomar', 'Rudi', 'Slamet', 'Taufik'];
    protected $namaWanita = ['Siti', 'Rina', 'Dewi', 'Nur', 'Lina', 'Rizka', 'Ayu', 'Maya', 'Fitri', 'Rani', 'Sari', 'Tika', 'Umi', 'Vina', 'Wulan', 'Yuni', 'Zahra', 'Intan', 'Jannah', 'Kartika'];
    protected $namaBelakang = ['Santoso', 'Wijaya', 'Hartono', 'Gunawan', 'Pratama', 'Saputra', 'Lestari', 'Ramadhani', 'Permata', 'Hidayah', 'Nugroho', 'Setiawan', 'Wibowo', 'Rahayu', 'Kusuma', 'Suryadi', 'Purnomo', 'Siregar', 'Hasibuan', 'Nasution'];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_keluarga')->truncate();
        DB::table('data_penduduk')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Wilayah Kudus (valid)
        $provLokal = 33;    
        $kabLokal  = 3319;
        $kecLokal  = 331902;
        $desaLokal = 3319021001;

        // Wilayah luar (aman)
        $wilayahLuar = [
            ['prov' => 15, 'kab' => 1501, 'kec' => 150101, 'desa' => 1501012001], // Jakarta Selatan
            ['prov' => 15, 'kab' => 1501, 'kec' => 150101, 'desa' => 1501012002], // Kota Bandung (3275 lebih umum)
            ['prov' => 15, 'kab' => 1501, 'kec' => 150101, 'desa' => 1501012003], // Kota Surakarta (Solo)
            ['prov' => 15, 'kab' => 1501, 'kec' => 150101, 'desa' => 1501011004], // Kota Surabaya
            ['prov' => 15, 'kab' => 1501, 'kec' => 150101, 'desa' => 1501012005], // Kota Tangerang
            ['prov' => 15, 'kab' => 1501, 'kec' => 150101, 'desa' => 1501012006], // Kota Denpasar
        ];

        $dusunList = [1 => 'Winong', 2 => 'Krajan', 3 => 'Ploso', 4 => 'Gondang', 5 => 'Ngemplak'];
        $dataKeluarga = [];
        $dataPenduduk = [];

        $nikCounter = 100000;
        $jumlahKK = 100;

        for ($i = 1; $i <= $jumlahKK; $i++) {
            $noKK = '33741234' . str_pad($i, 8, '0', STR_PAD_LEFT);
            $dusunId = array_rand($dusunList);
            $rw = str_pad(rand(1, 20), 3, '0', STR_PAD_LEFT);
            $rt = str_pad(rand(1, 20), 3, '0', STR_PAD_LEFT);

            // 15% mutasi datang
            $mutasiDatang = rand(1, 100) <= 15;

            if ($mutasiDatang) {
                $luar = $wilayahLuar[array_rand($wilayahLuar)];
                $provinsiAsal   = $luar['prov'];
                $kabupatenAsal  = $luar['kab'];
                $kecamatanAsal  = $luar['kec'];
                $desaAsal       = $luar['desa'];
                $kdmutasimasuk  = 3;
            } else {
                $provinsiAsal   = null;
                $kabupatenAsal  = null;
                $kecamatanAsal  = null;
                $desaAsal       = null;
                $kdmutasimasuk  = rand(1, 2);
            }

            $isKKLaki = rand(1, 100) <= 78;
            $namaKK = $isKKLaki
                ? $this->namaPria[array_rand($this->namaPria)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)]
                : $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];

            // Insert keluarga
            $dataKeluarga[] = [
                'no_kk'                  => $noKK,
                'kdmutasimasuk'          => $kdmutasimasuk,
                'keluarga_tanggalmutasi' => now(),
                'keluarga_kepalakeluarga'=> $namaKK,
                'kddusun'                => $dusunId,
                'keluarga_rw'            => $rw,
                'keluarga_rt'            => $rt,
                'keluarga_alamatlengkap' => 'Jl. ' . fake()->streetName() . ' No. ' . rand(1, 150),

                'kdprovinsi'   => $mutasiDatang ? $provinsiAsal   : null,
                'kdkabupaten'  => $mutasiDatang ? $kabupatenAsal  : null,
                'kdkecamatan'  => $mutasiDatang ? $kecamatanAsal  : null,
                'kddesa'       => $mutasiDatang ? $desaAsal       : null,
            ];

            // Urutan anggota dalam KK
            $urut = 1;

            // Kepala keluarga
            $nikCounter++;
            $dataPenduduk[] = $this->buatPenduduk([
                'nik' => '33741234' . str_pad($nikCounter, 8, '0', STR_PAD_LEFT),
                'no_kk' => $noKK,
                'nama' => $namaKK,
                'jk' => $isKKLaki ? 1 : 2,
                'hub' => 1,
                'urut' => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                'mutasi' => $kdmutasimasuk,
                'prov' => $provinsiAsal,
                'kab' => $kabupatenAsal,
                'kec' => $kecamatanAsal,
                'desa' => $desaAsal,
            ]);

            // Istri
            if ($isKKLaki && rand(1, 100) <= 88) {
                $nikCounter++;
                $namaIstri = $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];

                $dataPenduduk[] = $this->buatPenduduk([
                    'nik' => '33741234' . str_pad($nikCounter, 8, '0', STR_PAD_LEFT),
                    'no_kk' => $noKK,
                    'nama' => $namaIstri,
                    'jk' => 2,
                    'hub' => 2,
                    'urut' => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                    'mutasi' => $kdmutasimasuk,
                    'prov' => $provinsiAsal,
                    'kab' => $kabupatenAsal,
                    'kec' => $kecamatanAsal,
                    'desa' => $desaAsal,
                ]);
            }

            // Anak (0–4)
            $anakCount = rand(0, 4);
            for ($a = 0; $a < $anakCount; $a++) {
                $nikCounter++;
                $jk = rand(1, 2);
                $namaAnak = ($jk == 1 ? $this->namaPria : $this->namaWanita)[array_rand($jk == 1 ? $this->namaPria : $this->namaWanita)]
                    . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];

                $dataPenduduk[] = $this->buatPenduduk([
                    'nik' => '33741234' . str_pad($nikCounter, 8, '0', STR_PAD_LEFT),
                    'no_kk' => $noKK,
                    'nama' => $namaAnak,
                    'jk' => $jk,
                    'hub' => 3,
                    'urut' => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                    'mutasi' => $kdmutasimasuk,
                    'prov' => $provinsiAsal,
                    'kab' => $kabupatenAsal,
                    'kec' => $kecamatanAsal,
                    'desa' => $desaAsal,
                ]);
            }
        }

        DB::table('data_keluarga')->insert($dataKeluarga);
        DB::table('data_penduduk')->insert($dataPenduduk);

        $this->command->info("Seeder selesai! {$jumlahKK} KK + penduduk lengkap tanpa error.");
    }


    private function buatPenduduk($d)
    {
        return [
            'nik' => $d['nik'],
            'no_kk' => $d['no_kk'],

            'kdmutasimasuk' => $d['mutasi'],
            'penduduk_tanggalmutasi' => now(),

            'penduduk_kewarganegaraan' => 'INDONESIA',
            'penduduk_nourutkk' => $d['urut'],

            'penduduk_goldarah' => ['A','B','AB','O'][rand(0,3)],
            'penduduk_noaktalahir' => 'AKL-' . date('Y') . rand(10000, 99999),

            'penduduk_namalengkap' => $d['nama'],
            'penduduk_tempatlahir' => 'Kudus',
            'penduduk_tanggallahir' => now()->subYears(rand(1, 75))->format('Y-m-d'),

            'kdjeniskelamin' => $d['jk'],
            'kdagama' => rand(1, 6),
            'kdhubungankeluarga' => $d['hub'],
            'kdhubungankepalakeluarga' => $d['hub'],
            'kdstatuskawin' => $d['hub'] == 3 ? 1 : 2,
            'kdaktanikah' => rand(1, 3),
            'kdtercantumdalamkk' => 1,
            'kdstatustinggal' => 1,
            'kdkartuidentitas' => 1,
            'kdpekerjaan' => rand(1, 20),

            'penduduk_namaayah' => $this->namaPria[array_rand($this->namaPria)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)],
            'penduduk_namaibu'  => $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)],

            'penduduk_namatempatbekerja' => rand(1, 100) <= 60 ? fake()->company() : null,

            'kdprovinsi'   => $d['prov'],
            'kdkabupaten'  => $d['kab'],
            'kdkecamatan'  => $d['kec'],
            'kddesa'       => $d['desa'],
        ];
    }
}
