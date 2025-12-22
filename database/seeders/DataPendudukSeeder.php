<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DataPendudukSeeder extends Seeder
{
    protected $faker;
    protected $namaPria = ['Ahmad', 'Budi', 'Candra', 'Dedi', 'Eko', 'Fajar', 'Galih', 'Hadi', 'Irfan', 'Joko', 'Kurniawan', 'Lukman', 'Maman', 'Nico', 'Oka', 'Putra', 'Qomar', 'Rudi', 'Slamet', 'Taufik', 'Agus', 'Bambang', 'Dwi', 'Eka', 'Hendra', 'Indra', 'Krisna', 'Muhammad', 'Sigit', 'Wahyu'];
    protected $namaWanita = ['Siti', 'Rina', 'Dewi', 'Nur', 'Lina', 'Rizka', 'Ayu', 'Maya', 'Fitri', 'Rani', 'Sari', 'Tika', 'Umi', 'Vina', 'Wulan', 'Yuni', 'Zahra', 'Intan', 'Jannah', 'Kartika', 'Ani', 'Desi', 'Eka', 'Fatmawati', 'Hani', 'Indah', 'Lestari', 'Mira', 'Novi', 'Puji'];
    protected $namaBelakang = ['Santoso', 'Wijaya', 'Hartono', 'Gunawan', 'Pratama', 'Saputra', 'Lestari', 'Ramadhani', 'Permata', 'Hidayah', 'Nugroho', 'Setiawan', 'Wibowo', 'Rahayu', 'Kusuma', 'Suryadi', 'Purnomo', 'Siregar', 'Hasibuan', 'Nasution', 'Abdullah', 'Hidayat', 'Suprapto', 'Suharto', 'Yulianto'];

    protected $dusunList = ['KALIWUNGU', 'GERUNG', 'TEGUHAN', 'JETIS', 'PROKO WINONG'];
    protected $dusunMap = [
        'KALIWUNGU'    => 1,
        'GERUNG'       => 2,
        'TEGUHAN'      => 3,
        'JETIS'        => 4,
        'PROKO WINONG' => 5,
    ];

    protected $jalanList = [
        'Jl. Raya Kudus - Jepara', 'Jl. Kaliwungu', 'Jl. Honocoroko', 'Jl. Masjid', 'Jl. Pemuda',
        'Jl. Ahmad Yani', 'Jl. Sunan Muria', 'Jl. KH. Wahid Hasyim', 'Jl. Mejobo', 'Jl. Raya Jepara'
    ];

    // Kode pekerjaan realistis pedesaan Kudus
    protected $pekerjaan = [
        1  => ['Petani', ['Sawah Milik Sendiri', 'Sawah Sewa', 'Ladang Milik Sendiri']],
        2  => ['Buruh Tani', ['Buruh Tani Harian', 'Buruh Tani Musiman']],
        4  => ['Wiraswasta', ['Toko Kelontong', 'Warung Makan', 'Bengkel Motor', 'Konveksi Rumahan', 'Tukang Kayu']],
        5  => ['Pedagang', ['Pasar Kaliwungu', 'Pasar Tradisional', 'Pedagang Kaki Lima']],
        7  => ['Karyawan Swasta', ['PT. Djarum', 'PT. Pura Group', 'PT. Nojorono', 'Pabrik Rokok Kudus']],
        10 => ['PNS', ['Kantor Desa Kaliwungu', 'Puskesmas Kaliwungu', 'SDN Kaliwungu', 'SMPN 1 Kaliwungu']],
        11 => ['Guru', ['SDN 1 Kaliwungu', 'MI Ma’arif', 'MTs Al-Hidayah', 'SMA PGRI Kaliwungu']],
        14 => ['Ibu Rumah Tangga', null],
        15 => ['Pelajar/Mahasiswa', null],
        20 => ['Belum/Tidak Bekerja', null],
    ];

    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_keluarga')->truncate();
        DB::table('data_penduduk')->truncate();

        $wilayahLuar = [
            ['prov' => 31, 'kab' => 3171, 'kec' => 3171010, 'desa' => 3171010001], // Jakarta Pusat
            ['prov' => 32, 'kab' => 3273, 'kec' => 3273010, 'desa' => 3273010001], // Bandung
            ['prov' => 33, 'kab' => 3374, 'kec' => 3374010, 'desa' => 3374010001], // Semarang
            ['prov' => 35, 'kab' => 3578, 'kec' => 3578010, 'desa' => 3578010001], // Surabaya
            ['prov' => 36, 'kab' => 3671, 'kec' => 3671010, 'desa' => 3671010001], // Tangerang
        ];

        $jumlahKK   = 1200;
        $batchSize  = 500;
        $nikCounter = 331901000000;
        $totalPenduduk = 0;

        for ($batchStart = 0; $batchStart < $jumlahKK; $batchStart += $batchSize) {
            $dataKeluarga = [];
            $dataPenduduk = [];

            $batchEnd = min($batchStart + $batchSize, $jumlahKK);

            for ($i = $batchStart + 1; $i <= $batchEnd; $i++) {
                $noKK = '3319012001' . str_pad($i, 6, '0', STR_PAD_LEFT);

                $dusunNama = $this->dusunList[array_rand($this->dusunList)];
                $kddusunId = $this->dusunMap[$dusunNama];
                $rw = str_pad(rand(1, 12), 3, '0', STR_PAD_LEFT);
                $rt = str_pad(rand(1, 15), 3, '0', STR_PAD_LEFT);

                // Distribusi mutasi sesuai 4 jenis
                // 70% MUTASI LAHIR (kd = 2)
                // 20% MUTASI TETAP (kd = 1)
                // 8% MUTASI DATANG (kd = 3)
                // 2% MUTASI KELUAR (kd = 4) — tetap dianggap ada di KK untuk data historis
                $randMutasi = rand(1, 100);
                if ($randMutasi <= 70) {
                    $kdmutasimasuk = 2; // MUTASI LAHIR
                    $provinsiAsal = $kabupatenAsal = $kecamatanAsal = $desaAsal = null;
                } elseif ($randMutasi <= 90) {
                    $kdmutasimasuk = 1; // MUTASI TETAP
                    $provinsiAsal = $kabupatenAsal = $kecamatanAsal = $desaAsal = null;
                } elseif ($randMutasi <= 98) {
                    $kdmutasimasuk = 3; // MUTASI DATANG
                    $luar = $wilayahLuar[array_rand($wilayahLuar)];
                    $provinsiAsal = $luar['prov'];
                    $kabupatenAsal = $luar['kab'];
                    $kecamatanAsal = $luar['kec'];
                    $desaAsal = $luar['desa'];
                } else {
                    $kdmutasimasuk = 4; // MUTASI KELUAR (tetap ada di KK untuk data)
                    $provinsiAsal = $kabupatenAsal = $kecamatanAsal = $desaAsal = null;
                }

                $isKKLaki = rand(1, 100) <= 78;
                $namaKK = $isKKLaki
                    ? $this->namaPria[array_rand($this->namaPria)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)]
                    : $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];

                $jalan = $this->jalanList[array_rand($this->jalanList)];
                $nomor = rand(1, 150);
                $alamatLengkap = "Dusun {$dusunNama}, RT {$rt}/RW {$rw}, Desa Kaliwungu, Kec. Kaliwungu, Kab. Kudus";

                $dataKeluarga[] = [
                    'no_kk'                  => $noKK,
                    'kdmutasimasuk'          => $kdmutasimasuk,
                    'keluarga_tanggalmutasi' => now(),
                    'keluarga_kepalakeluarga'=> $namaKK,
                    'kddusun'                => $kddusunId,
                    'keluarga_rw'            => $rw,
                    'keluarga_rt'            => $rt,
                    'keluarga_alamatlengkap' => "{$jalan} No. {$nomor}, {$alamatLengkap}",
                    'kdprovinsi'             => $provinsiAsal,
                    'kdkabupaten'            => $kabupatenAsal,
                    'kdkecamatan'            => $kecamatanAsal,
                    'kddesa'                 => $desaAsal,
                ];

                $urut = 1;

                // 1. Kepala Keluarga
                $nikCounter++;
                $dataPenduduk[] = $this->buatPenduduk([
                    'nik'    => '3319' . str_pad($nikCounter, 12, '0', STR_PAD_LEFT),
                    'no_kk'  => $noKK,
                    'nama'   => $namaKK,
                    'jk'     => $isKKLaki ? 1 : 2,
                    'hub'    => 1,
                    'urut'   => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                    'mutasi' => $kdmutasimasuk,
                    'prov'   => $provinsiAsal,
                    'kab'    => $kabupatenAsal,
                    'kec'    => $kecamatanAsal,
                    'desa'   => $desaAsal,
                    'umur'   => rand(28, 75),
                ]);

                // 2. Pasangan (hanya jika KK laki-laki, 88% punya istri)
                if ($isKKLaki && rand(1, 100) <= 88) {
                    $nikCounter++;
                    $namaIstri = $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];
                    $dataPenduduk[] = $this->buatPenduduk([
                        'nik'    => '3319' . str_pad($nikCounter, 12, '0', STR_PAD_LEFT),
                        'no_kk'  => $noKK,
                        'nama'   => $namaIstri,
                        'jk'     => 2,
                        'hub'    => 2,
                        'urut'   => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                        'mutasi' => $kdmutasimasuk,
                        'prov'   => $provinsiAsal,
                        'kab'    => $kabupatenAsal,
                        'kec'    => $kecamatanAsal,
                        'desa'   => $desaAsal,
                        'umur'   => rand(25, 65),
                    ]);
                }

                // 3. Anak-anak
                $anakCount = rand(0, 5);
                for ($a = 0; $a < $anakCount; $a++) {
                    $nikCounter++;
                    $jkAnak = rand(1, 2);
                    $namaAnak = ($jkAnak == 1 ? $this->namaPria : $this->namaWanita)[array_rand($jkAnak == 1 ? $this->namaPria : $this->namaWanita)]
                        . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];

                    $umurAnak = rand(0, 30);
                    if ($umurAnak > 22) $umurAnak = rand(0, 22); // jarang anak >22 tinggal di KK

                    $dataPenduduk[] = $this->buatPenduduk([
                        'nik'    => '3319' . str_pad($nikCounter, 12, '0', STR_PAD_LEFT),
                        'no_kk'  => $noKK,
                        'nama'   => $namaAnak,
                        'jk'     => $jkAnak,
                        'hub'    => 3,
                        'urut'   => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                        'mutasi' => $kdmutasimasuk,
                        'prov'   => $provinsiAsal,
                        'kab'    => $kabupatenAsal,
                        'kec'    => $kecamatanAsal,
                        'desa'   => $desaAsal,
                        'umur'   => $umurAnak,
                    ]);
                }
            }

            DB::table('data_keluarga')->insert($dataKeluarga);
            DB::table('data_penduduk')->insert($dataPenduduk);
            $totalPenduduk += count($dataPenduduk);

            $this->command->info("Batch KK " . ($batchStart + 1) . " - {$batchEnd} selesai (" . count($dataPenduduk) . " penduduk)");
            unset($dataKeluarga, $dataPenduduk);
            gc_collect_cycles();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info("SEEDER SELESAI! {$jumlahKK} KK dan ±{$totalPenduduk} penduduk berhasil dibuat.");
    }

    private function buatPenduduk($d)
    {
        $umur = $d['umur'] ?? rand(1, 80);
        $isDewasa = $umur >= 17;
        $isMenikah = in_array($d['hub'], [1, 2]) || ($d['hub'] == 3 && $umur >= 20 && rand(1,100) <= 15);

        // Agama: 94% Islam, 5% Kristen Protestan, 1% Katolik
        $randAgama = rand(1, 100);
        if ($randAgama <= 94) {
            $kdagama = 1; // Islam
        } elseif ($randAgama <= 99) {
            $kdagama = 2; // Kristen Protestan
        } else {
            $kdagama = 3; // Katolik
        }

        // Pekerjaan realistis
        $pekerjaanKeys = array_keys($this->pekerjaan);
        $kdpekerjaan = $pekerjaanKeys[array_rand($pekerjaanKeys)];
        $namaPekerjaan = $this->pekerjaan[$kdpekerjaan][0];
        $tempatKerja = $this->pekerjaan[$kdpekerjaan][1] ? $this->pekerjaan[$kdpekerjaan][1][array_rand($this->pekerjaan[$kdpekerjaan][1])] : null;

        // Ibu rumah tangga hanya untuk perempuan dewasa
        if ($d['jk'] == 2 && $umur >= 25 && $umur <= 60 && rand(1,100) <= 45) {
            $kdpekerjaan = 14;
            $namaPekerjaan = 'Ibu Rumah Tangga';
            $tempatKerja = null;
        }

        // Pelajar untuk umur < 23
        if ($umur <= 22 && rand(1,100) <= 80) {
            $kdpekerjaan = 15;
            $namaPekerjaan = 'Pelajar/Mahasiswa';
            $tempatKerja = null;
        }

        return [
            'nik'                       => $d['nik'],
            'no_kk'                     => $d['no_kk'],
            'kdmutasimasuk'             => $d['mutasi'],
            'penduduk_tanggalmutasi'    => now(),
            'penduduk_kewarganegaraan'  => 'WNI',
            'penduduk_nourutkk'         => $d['urut'],
            'penduduk_goldarah'         => ['A','B','AB','O'][rand(0,3)],
            'penduduk_noaktalahir'      => 'AKL/' . rand(1,999) . '/' . date('Y'),

            'penduduk_namalengkap'      => $d['nama'],
            'penduduk_tempatlahir'      => rand(1,10) <= 8 ? 'KUDUS' : 'SEMARANG',
            'penduduk_tanggallahir'     => now()->subYears($umur)->subDays(rand(0,365))->format('Y-m-d'),

            'kdjeniskelamin'            => $d['jk'],
            'kdagama'                   => $kdagama,
            'kdhubungankeluarga'        => $d['hub'],
            'kdhubungankepalakeluarga'  => $d['hub'],

            'kdstatuskawin'             => $isMenikah ? 2 : 1,
            'kdaktanikah'               => $isMenikah ? 2 : 1,
            'kdtercantumdalamkk'        => $isMenikah ? 2 : 1,

            'kdstatustinggal'           => 1,
            'kdkartuidentitas'          => 1,
            'kdpekerjaan'               => $kdpekerjaan,

            'penduduk_namaayah'         => $this->namaPria[array_rand($this->namaPria)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)],
            'penduduk_namaibu'          => $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)],

            'penduduk_namatempatbekerja'=> $tempatKerja,

            'kdprovinsi'                => $d['prov'],
            'kdkabupaten'               => $d['kab'],
            'kdkecamatan'               => $d['kec'],
            'kddesa'                    => $d['desa'],
        ];
    }
}