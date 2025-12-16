<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataPendudukSeeder extends Seeder
{

    protected $namaPria = ['Ahmad', 'Budi', 'Candra', 'Dedi', 'Eko', 'Fajar', 'Galih', 'Hadi', 'Irfan', 'Joko', 'Kurniawan', 'Lukman', 'Maman', 'Nico', 'Oka', 'Putra', 'Qomar', 'Rudi', 'Slamet', 'Taufik', 'Agus', 'Bambang', 'Dwi', 'Eka', 'Hendra', 'Indra', 'Krisna', 'Muhammad', 'Sigit', 'Wahyu'];
    protected $namaWanita = ['Siti', 'Rina', 'Dewi', 'Nur', 'Lina', 'Rizka', 'Ayu', 'Maya', 'Fitri', 'Rani', 'Sari', 'Tika', 'Umi', 'Vina', 'Wulan', 'Yuni', 'Zahra', 'Intan', 'Jannah', 'Kartika', 'Ani', 'Desi', 'Eka', 'Fatmawati', 'Hani', 'Indah', 'Lestari', 'Mira', 'Novi', 'Puji'];
    protected $namaBelakang = ['Santoso', 'Wijaya', 'Hartono', 'Gunawan', 'Pratama', 'Saputra', 'Lestari', 'Ramadhani', 'Permata', 'Hidayah', 'Nugroho', 'Setiawan', 'Wibowo', 'Rahayu', 'Kusuma', 'Suryadi', 'Purnomo', 'Siregar', 'Hasibuan', 'Nasution', 'Abdullah', 'Hidayat', 'Suprapto', 'Suharto', 'Yulianto'];

    protected $dusunList = [
        'KALIWUNGU', 'GERUNG', 'TEGUHAN', 'JETIS', 'PROKO WINONG'
    ];

    protected $dusunMap = [
        'KALIWUNGU'    => 1,
        'GERUNG'       => 2,
        'TEGUHAN'      => 3,
        'JETIS'        => 4,
        'PROKO WINONG' => 5,
    ];

    protected $jalanList = [
        'Jl. Raya Kudus - Jepara', 'Jl. Kaliwungu', 'Jl. Honocoroko', 'Jl. Masjid', 'Jl. Pemuda', 'Jl. Ahmad Yani', 'Jl. Sunan Muria', 'Jl. KH. Wahid Hasyim'
    ];

    public function run(): void
    {
        // Matikan foreign key check sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('data_keluarga')->truncate();
        DB::table('data_penduduk')->truncate();

        $wilayahLuar = [
            ['prov' => 31, 'kab' => 3171, 'kec' => 317101, 'desa' => 3171010001], // Jakarta
            ['prov' => 32, 'kab' => 3275, 'kec' => 327501, 'desa' => 3275010001], // Bandung
            ['prov' => 33, 'kab' => 3374, 'kec' => 337401, 'desa' => 3374010001], // Semarang
            ['prov' => 35, 'kab' => 3578, 'kec' => 357801, 'desa' => 3578010001], // Surabaya
            ['prov' => 36, 'kab' => 3671, 'kec' => 367101, 'desa' => 3671010001], // Tangerang
        ];

        $jumlahKK   = 1200;
        $batchSize  = 500;          // Insert 500 KK per batch
        $nikCounter = 331901000000; // Basis NIK Kudus
        $totalPenduduk = 0;

        // Batch processing
        for ($batchStart = 0; $batchStart < $jumlahKK; $batchStart += $batchSize) {
            $dataKeluarga = [];
            $dataPenduduk = [];

            $batchEnd = min($batchStart + $batchSize, $jumlahKK);

            for ($i = $batchStart + 1; $i <= $batchEnd; $i++) {
                // No KK: 16 digit realistis (kode wilayah desa + urut 6 digit)
                $noKK = '3319012001' . str_pad($i, 6, '0', STR_PAD_LEFT);

                $dusunNama = $this->dusunList[array_rand($this->dusunList)];
                $kddusunId = $this->dusunMap[$dusunNama];
                $rw = str_pad(rand(1, 15), 2, '0', STR_PAD_LEFT);
                $rt = str_pad(rand(1, 20), 3, '0', STR_PAD_LEFT);

                // 15% mutasi datang dari luar
                $mutasiDatang = rand(1, 100) <= 15;
                if ($mutasiDatang) {
                    $luar = $wilayahLuar[array_rand($wilayahLuar)];
                    $provinsiAsal   = $luar['prov'];
                    $kabupatenAsal  = $luar['kab'];
                    $kecamatanAsal  = $luar['kec'];
                    $desaAsal       = $luar['desa'];
                    $kdmutasimasuk  = 3; // Mutasi datang
                } else {
                    $provinsiAsal = $kabupatenAsal = $kecamatanAsal = $desaAsal = null;
                    $kdmutasimasuk = rand(1, 2); // Lahir / lainnya
                }

                // 78% KK adalah laki-laki
                $isKKLaki = rand(1, 100) <= 78;
                $namaKK = $isKKLaki
                    ? $this->namaPria[array_rand($this->namaPria)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)]
                    : $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];

                $jalan = $this->jalanList[array_rand($this->jalanList)];
                $nomor = rand(1, 200);
                $alamatLengkap = "Dusun {$dusunNama}, RT {$rt}/RW {$rw}, Desa Kaliwungu, Kecamatan Kaliwungu, Kabupaten Kudus";

                // Insert ke data_keluarga
                $dataKeluarga[] = [
                    'no_kk'                  => $noKK,
                    'kdmutasimasuk'          => $kdmutasimasuk,
                    'keluarga_tanggalmutasi' => now(),
                    'keluarga_kepalakeluarga'=> $namaKK,
                    'kddusun'                => $kddusunId,
                    'keluarga_rw'            => $rw,
                    'keluarga_rt'            => $rt,
                    'keluarga_alamatlengkap' => "{$jalan} No. {$nomor}, {$alamatLengkap}",
                    'kdprovinsi'             => $mutasiDatang ? $provinsiAsal : null,
                    'kdkabupaten'            => $mutasiDatang ? $kabupatenAsal : null,
                    'kdkecamatan'            => $mutasiDatang ? $kecamatanAsal : null,
                    'kddesa'                 => $mutasiDatang ? $desaAsal : null,
                ];

                $urut = 1;

                // 1. Kepala Keluarga
                $nikCounter++;
                $dataPenduduk[] = $this->buatPenduduk([
                    'nik'    => '3319' . str_pad($nikCounter, 12, '0', STR_PAD_LEFT),
                    'no_kk'  => $noKK,
                    'nama'   => $namaKK,
                    'jk'     => $isKKLaki ? 1 : 2,
                    'hub'    => 1, // Kepala Keluarga
                    'urut'   => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                    'mutasi' => $kdmutasimasuk,
                    'prov'   => $provinsiAsal,
                    'kab'    => $kabupatenAsal,
                    'kec'    => $kecamatanAsal,
                    'desa'   => $desaAsal,
                ]);

                // 2. Pasangan (istri/suami) - hanya jika KK laki-laki dan probabilitas 88%
                if ($isKKLaki && rand(1, 100) <= 88) {
                    $nikCounter++;
                    $namaPasangan = $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];
                    $dataPenduduk[] = $this->buatPenduduk([
                        'nik'    => '3319' . str_pad($nikCounter, 12, '0', STR_PAD_LEFT),
                        'no_kk'  => $noKK,
                        'nama'   => $namaPasangan,
                        'jk'     => 2,
                        'hub'    => 2, // Istri
                        'urut'   => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                        'mutasi' => $kdmutasimasuk,
                        'prov'   => $provinsiAsal,
                        'kab'    => $kabupatenAsal,
                        'kec'    => $kecamatanAsal,
                        'desa'   => $desaAsal,
                    ]);
                }

                // 3. Anak-anak (0-5 orang)
                $anakCount = rand(0, 5);
                for ($a = 0; $a < $anakCount; $a++) {
                    $nikCounter++;
                    $jkAnak = rand(1, 2);
                    $namaAnak = ($jkAnak == 1 ? $this->namaPria : $this->namaWanita)[array_rand($jkAnak == 1 ? $this->namaPria : $this->namaWanita)]
                        . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];

                    $dataPenduduk[] = $this->buatPenduduk([
                        'nik'    => '3319' . str_pad($nikCounter, 12, '0', STR_PAD_LEFT),
                        'no_kk'  => $noKK,
                        'nama'   => $namaAnak,
                        'jk'     => $jkAnak,
                        'hub'    => 3, // Anak
                        'urut'   => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                        'mutasi' => $kdmutasimasuk,
                        'prov'   => $provinsiAsal,
                        'kab'    => $kabupatenAsal,
                        'kec'    => $kecamatanAsal,
                        'desa'   => $desaAsal,
                    ]);
                }
            }

            // Insert batch ini
            DB::table('data_keluarga')->insert($dataKeluarga);
            DB::table('data_penduduk')->insert($dataPenduduk);

            $totalPenduduk += count($dataPenduduk);

            $startKK = $batchStart + 1;

            $this->command->info("Batch selesai: KK {$startKK} - {$batchEnd} (penduduk batch: " . count($dataPenduduk) . ")");

            // Bersihkan memory
            unset($dataKeluarga, $dataPenduduk);
            gc_collect_cycles();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info("Seeder SELESAI! Total {$jumlahKK} Kartu Keluarga dengan ±{$totalPenduduk} penduduk telah berhasil dibuat (dalam batch 500).");
    }

    private function buatPenduduk($d)
    {
        $umur = rand(1, 80);

        return [
            'nik'                       => $d['nik'],
            'no_kk'                     => $d['no_kk'],

            'kdmutasimasuk'             => $d['mutasi'],
            'penduduk_tanggalmutasi'    => now(),

            'penduduk_kewarganegaraan'  => 'INDONESIA',
            'penduduk_nourutkk'         => $d['urut'],

            'penduduk_goldarah'         => ['A','B','AB','O'][rand(0,3)],
            'penduduk_noaktalahir'      => 'AKL-' . date('Y') . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),

            'penduduk_namalengkap'      => $d['nama'],
            'penduduk_tempatlahir'      => rand(1,10) <= 8 ? 'Kudus' : 'Semarang',
            'penduduk_tanggallahir'     => now()->subYears($umur)->subDays(rand(0,365))->format('Y-m-d'),

            'kdjeniskelamin'            => $d['jk'],
            'kdagama'                   => rand(1, 6),
            'kdhubungankeluarga'        => $d['hub'],
            'kdhubungankepalakeluarga'  => $d['hub'],
            'kdstatuskawin'             => $d['hub'] == 3 ? 1 : (rand(1,100) <= 70 ? 2 : 1), // Anak belum kawin
            'kdaktanikah'               => rand(1, 3),
            'kdtercantumdalamkk'        => 1,
            'kdstatustinggal'           => 1,
            'kdkartuidentitas'          => 1,
            'kdpekerjaan'               => rand(1, 20),

            'penduduk_namaayah'         => $this->namaPria[array_rand($this->namaPria)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)],
            'penduduk_namaibu'          => $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)],

            'penduduk_namatempatbekerja'=> rand(1, 100) <= 70 ? fake()->company() : null,

            'kdprovinsi'                => $d['prov'],
            'kdkabupaten'               => $d['kab'],
            'kdkecamatan'               => $d['kec'],
            'kddesa'                    => $d['desa'],
        ];
    }
}