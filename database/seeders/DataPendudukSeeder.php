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
        'KALIWUNGU' => 1,
        'GERUNG' => 2,
        'TEGUHAN' => 3,
        'JETIS' => 4,
        'PROKO WINONG' => 5,
    ];

    protected $jalanList = [
        'Jl. Raya Kudus - Jepara', 'Jl. Kaliwungu', 'Jl. Honocoroko', 'Jl. Masjid', 'Jl. Pemuda',
        'Jl. Ahmad Yani', 'Jl. Sunan Muria', 'Jl. KH. Wahid Hasyim', 'Jl. Mejobo', 'Jl. Raya Jepara'
    ];

    protected $pekerjaan = [
        1 => ['Petani', ['Sawah Milik Sendiri', 'Sawah Sewa', 'Ladang Milik Sendiri']],
        2 => ['Buruh Tani', ['Buruh Tani Harian', 'Buruh Tani Musiman']],
        4 => ['Wiraswasta', ['Toko Kelontong', 'Warung Makan', 'Bengkel Motor', 'Konveksi Rumahan', 'Tukang Kayu']],
        5 => ['Pedagang', ['Pasar Kaliwungu', 'Pasar Tradisional', 'Pedagang Kaki Lima']],
        7 => ['Karyawan Swasta', ['PT. Djarum', 'PT. Pura Group', 'PT. Nojorono', 'Pabrik Rokok Kudus']],
        10 => ['PNS', ['Kantor Desa', 'Puskesmas', 'SDN', 'SMPN 1']],
        11 => ['Guru', ['SDN 1', 'MI Ma’arif', 'MTs Al-Hidayah', 'SMA PGRI']],
        14 => ['Ibu Rumah Tangga', null],
        15 => ['Pelajar/Mahasiswa', null],
        20 => ['Belum/Tidak Bekerja', null],
    ];

    protected $wilayah = [
        '001' => ['rt' => ['01' => 45, '02' => 40, '03' => 50, '04' => 42, '05' => 38]],
        '002' => ['rt' => ['01' => 55, '02' => 60, '03' => 58, '04' => 52, '05' => 45, '06' => 50]],
        '003' => ['rt' => ['01' => 35, '02' => 40, '03' => 32, '04' => 30, '05' => 42, '06' => 38, '07' => 45]],
        '004' => ['rt' => ['01' => 40, '02' => 45, '03' => 50, '04' => 58, '05' => 40]],
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
            ['prov' => 31, 'kab' => 3171, 'kec' => 3171010, 'desa' => 3171010001],
            ['prov' => 32, 'kab' => 3273, 'kec' => 3273010, 'desa' => 3273010001],
            ['prov' => 33, 'kab' => 3374, 'kec' => 3374010, 'desa' => 3374010001],
            ['prov' => 35, 'kab' => 3578, 'kec' => 3578010, 'desa' => 3578010001],
            ['prov' => 36, 'kab' => 3671, 'kec' => 3671010, 'desa' => 3671010001],
        ];

        $batchSize = 500;
        $nikCounter = 331901000000;
        $totalPenduduk = 0;
        $kkCounter = 0;

        $dataKeluarga = [];
        $dataPendudukBatch = [];

        foreach ($this->wilayah as $rw => $info) {
        $rw3 = str_pad($rw, 3, '0', STR_PAD_LEFT);
            foreach ($info['rt'] as $rt => $jumlahKKdiRT) {
                $rt3 = str_pad($rt, 3, '0', STR_PAD_LEFT);
                for ($i = 0; $i < $jumlahKKdiRT; $i++) {
                    $kkCounter++;
                    $noKK = '3319012001' . str_pad($kkCounter, 6, '0', STR_PAD_LEFT);

                    $dusunNama = $this->dusunList[array_rand($this->dusunList)];
                    $kddusunId = $this->dusunMap[$dusunNama];

                    // Mutasi
                    $randMutasi = rand(1, 100);
                    if ($randMutasi <= 70) {
                        $kdmutasimasuk = 2; // Lahir
                        $provAsal = $kabAsal = $kecAsal = $desaAsal = null;
                    } elseif ($randMutasi <= 90) {
                        $kdmutasimasuk = 1; // Tetap
                        $provAsal = $kabAsal = $kecAsal = $desaAsal = null;
                    } elseif ($randMutasi <= 98) {
                        $kdmutasimasuk = 3; // Datang
                        $luar = $wilayahLuar[array_rand($wilayahLuar)];
                        $provAsal = $luar['prov'];
                        $kabAsal = $luar['kab'];
                        $kecAsal = $luar['kec'];
                        $desaAsal = $luar['desa'];
                    } else {
                        $kdmutasimasuk = 4; // Keluar
                        $provAsal = $kabAsal = $kecAsal = $desaAsal = null;
                    }

                    $isKKLaki = rand(1, 100) <= 78;
                    $namaKK = $isKKLaki
                        ? $this->namaPria[array_rand($this->namaPria)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)]
                        : $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];

                    $alamatLengkap = "Dusun {$dusunNama}, RT {$rt3}/RW {$rw3}, Desa Kaliwungu";

                    $dataKeluarga[] = [
                        'no_kk' => $noKK,
                        'kdmutasimasuk' => $kdmutasimasuk,
                        'keluarga_tanggalmutasi' => now(),
                        'keluarga_kepalakeluarga' => $namaKK,
                        'kddusun' => $kddusunId,
                        'keluarga_rw' => $rw3,
                        'keluarga_rt' => $rt3,
                        'keluarga_alamatlengkap' => $alamatLengkap,
                        'kdprovinsi' => $provAsal,
                        'kdkabupaten' => $kabAsal,
                        'kdkecamatan' => $kecAsal,
                        'kddesa' => $desaAsal,
                    ];

                    $urut = 1;
                    $anggotaKeluarga = [];
                    $punyaPasangan = false; // Flag untuk logika status kawin KK

                    // 1. Kepala Keluarga
                    $nikCounter++;
                    $umurKK = rand(28, 75);
                    $anggotaKeluarga[] = $this->buatPenduduk([
                        'nik' => '3319' . str_pad($nikCounter, 12, '0', STR_PAD_LEFT),
                        'no_kk' => $noKK,
                        'nama' => $namaKK,
                        'jk' => $isKKLaki ? 1 : 2,
                        'hub' => 1,
                        'urut' => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                        'mutasi' => $kdmutasimasuk,
                        'prov' => $provAsal,
                        'kab' => $kabAsal,
                        'kec' => $kecAsal,
                        'desa' => $desaAsal,
                        'umur' => $umurKK,
                        'status_kawin_override' => null, // akan ditentukan nanti
                    ]);

                    // 2. Pasangan (istri/suami)
                    if (($isKKLaki && rand(1, 100) <= 88) || (!$isKKLaki && rand(1, 100) <= 85)) {
                        $nikCounter++;
                        $namaPasangan = $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];
                        $umurPasangan = max(20, $umurKK + rand(-10, 8));

                        $anggotaKeluarga[] = $this->buatPenduduk([
                            'nik' => '3319' . str_pad($nikCounter, 12, '0', STR_PAD_LEFT),
                            'no_kk' => $noKK,
                            'nama' => $namaPasangan,
                            'jk' => 2,
                            'hub' => 2,
                            'urut' => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                            'mutasi' => $kdmutasimasuk,
                            'prov' => $provAsal,
                            'kab' => $kabAsal,
                            'kec' => $kecAsal,
                            'desa' => $desaAsal,
                            'umur' => $umurPasangan,
                            'status_kawin_override' => 2, // Pasti kawin karena ada pasangan di KK
                        ]);

                        $punyaPasangan = true;
                    }

                    // Update status kawin KK jika punya pasangan
                    if ($punyaPasangan) {
                        $anggotaKeluarga[0]['kdstatuskawin'] = 2; // KAWIN
                        $anggotaKeluarga[0]['kdaktanikah'] = 2;
                    }

                    // 3. Anak-anak
                    $anakCount = rand(0, 5);
                    for ($a = 0; $a < $anakCount; $a++) {
                        $nikCounter++;
                        $jkAnak = rand(1, 2);
                        $namaAnak = ($jkAnak == 1 ? $this->namaPria : $this->namaWanita)[array_rand($jkAnak == 1 ? $this->namaPria : $this->namaWanita)]
                            . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];
                        $umurAnak = rand(0, 30);
                        if ($umurAnak > 22) $umurAnak = rand(0, 22);

                        $anggotaKeluarga[] = $this->buatPenduduk([
                            'nik' => '3319' . str_pad($nikCounter, 12, '0', STR_PAD_LEFT),
                            'no_kk' => $noKK,
                            'nama' => $namaAnak,
                            'jk' => $jkAnak,
                            'hub' => 3,
                            'urut' => str_pad($urut++, 2, '0', STR_PAD_LEFT),
                            'mutasi' => $kdmutasimasuk,
                            'prov' => $provAsal,
                            'kab' => $kabAsal,
                            'kec' => $kecAsal,
                            'desa' => $desaAsal,
                            'umur' => $umurAnak,
                            'status_kawin_override' => null,
                        ]);
                    }

                    $dataPendudukBatch = array_merge($dataPendudukBatch, $anggotaKeluarga);

                    if (count($dataKeluarga) >= $batchSize) {
                        DB::table('data_keluarga')->insert($dataKeluarga);
                        DB::table('data_penduduk')->insert($dataPendudukBatch);
                        $totalPenduduk += count($dataPendudukBatch);
                        $this->command->info("Batch hingga KK {$kkCounter} selesai");
                        $dataKeluarga = [];
                        $dataPendudukBatch = [];
                    }
                }
            }
        }

        if (!empty($dataKeluarga)) {
            DB::table('data_keluarga')->insert($dataKeluarga);
            DB::table('data_penduduk')->insert($dataPendudukBatch);
            $totalPenduduk += count($dataPendudukBatch);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info("SEEDER SELESAI! {$kkCounter} KK dan {$totalPenduduk} penduduk berhasil dibuat.");
    }

    private function buatPenduduk($d)
    {
        $umur = $d['umur'];
        $hub = $d['hub']; // 1=KK, 2=Pasangan, 3=Anak
        $override = $d['status_kawin_override'] ?? null;

        // Tentukan status kawin
        if ($override !== null) {
            $kdstatuskawin = $override;
        } elseif ($umur < 18) {
            $kdstatuskawin = 1; // Belum Kawin
        } elseif ($umur <= 25) {
            $kdstatuskawin = rand(1,100) <= 85 ? 1 : 2;
        } else {
            if ($hub == 1 || $hub == 2) {
                // KK atau pasangan tanpa pasangan di KK → janda/duda
                $kdstatuskawin = rand(1,100) <= 80 ? 4 : 3; // 80% Cerai Mati
            } else {
                // Anak dewasa
                $rand = rand(1,100);
                if ($rand <= 65) $kdstatuskawin = 2;
                elseif ($rand <= 90) $kdstatuskawin = 1;
                elseif ($rand <= 98) $kdstatuskawin = 4;
                else $kdstatuskawin = 3;
            }
        }

        // Agama
        $kdagama = rand(1,100) <= 94 ? 1 : (rand(1,100) <= 99 ? 2 : 3);

        // Pekerjaan
        $pekerjaanKeys = array_keys($this->pekerjaan);
        $kdpekerjaan = $pekerjaanKeys[array_rand($pekerjaanKeys)];
        $tempatKerja = $this->pekerjaan[$kdpekerjaan][1] 
            ? $this->pekerjaan[$kdpekerjaan][1][array_rand($this->pekerjaan[$kdpekerjaan][1])] 
            : null;

        if ($d['jk'] == 2 && $umur >= 25 && $umur <= 60 && rand(1,100) <= 45) {
            $kdpekerjaan = 14; // IRT
            $tempatKerja = null;
        }
        if ($umur <= 22 && rand(1,100) <= 80) {
            $kdpekerjaan = 15; // Pelajar
            $tempatKerja = null;
        }

        return [
            'nik' => $d['nik'],
            'no_kk' => $d['no_kk'],
            'kdmutasimasuk' => $d['mutasi'],
            'penduduk_tanggalmutasi' => now(),
            'penduduk_kewarganegaraan' => 'WNI',
            'penduduk_nourutkk' => $d['urut'],
            'penduduk_goldarah' => ['A','B','AB','O'][rand(0,3)],
            'penduduk_noaktalahir' => 'AKL/' . rand(1,999) . '/' . date('Y'),
            'penduduk_namalengkap' => $d['nama'],
            'penduduk_tempatlahir' => rand(1,10) <= 8 ? 'KUDUS' : 'SEMARANG',
            'penduduk_tanggallahir' => now()->subYears($umur)->subDays(rand(0,365))->format('Y-m-d'),
            'kdjeniskelamin' => $d['jk'],
            'kdagama' => $kdagama,
            'kdhubungankeluarga' => $d['hub'],
            'kdhubungankepalakeluarga' => $d['hub'],
            'kdstatuskawin' => $kdstatuskawin,
            'kdaktanikah' => $kdstatuskawin == 1 ? 1 : 2,
            'kdtercantumdalamkk' => 1,
            'kdstatustinggal' => 1,
            'kdkartuidentitas' => 1,
            'kdpekerjaan' => $kdpekerjaan,
            'penduduk_namaayah' => $this->namaPria[array_rand($this->namaPria)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)],
            'penduduk_namaibu' => $this->namaWanita[array_rand($this->namaWanita)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)],
            'penduduk_namatempatbekerja' => $tempatKerja,
            'kdprovinsi' => $d['prov'],
            'kdkabupaten' => $d['kab'],
            'kdkecamatan' => $d['kec'],
            'kddesa' => $d['desa'],
        ];
    }
}