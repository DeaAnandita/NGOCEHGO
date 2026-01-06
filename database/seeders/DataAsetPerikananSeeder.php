<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetPerikananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua no_kk dari data_keluarga
        $kkList = DB::table('data_keluarga')
            ->pluck('no_kk')
            ->toArray();

        if (empty($kkList)) {
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data aset perikanan yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data aset perikanan untuk {$totalKK} keluarga...");

        $batchSize = 500;
        $data = [];

        foreach ($kkList as $no_kk) {
            // Tentukan "tingkat ekonomi" keluarga secara acak untuk variasi realistis
            // 1 = miskin, 2 = menengah bawah, 3 = menengah atas, 4 = kaya
            $tingkatEkonomi = mt_rand(1, 100);
            if ($tingkatEkonomi <= 40) {
                $ekonomi = 1; // 40% miskin
            } elseif ($tingkatEkonomi <= 80) {
                $ekonomi = 2; // 40% menengah bawah
            } elseif ($tingkatEkonomi <= 95) {
                $ekonomi = 3; // 15% menengah atas
            } else {
                $ekonomi = 4; // 5% kaya
            }

            // Probabilitas dasar memiliki usaha/budidaya perikanan (tergantung ekonomi)
            // Di desa pesisir/pedesaan, sekitar 15-25% keluarga terlibat perikanan
            $chancePunyahPerikanan = $this->baseChance(15, $ekonomi); // base 15% + bonus ekonomi

            $punyaPerikanan = mt_rand(1, 100) <= $chancePunyahPerikanan;

            // Inisialisasi semua aset perikanan ke 0 (jumlah)
            $row = [
                'no_kk' => $no_kk,
                'asetperikanan_1' => 0, // KERAMBA
                'asetperikanan_2' => 0, // TAMBAK
                'asetperikanan_3' => 0, // JERMAL
                'asetperikanan_4' => 0, // PANCING
                'asetperikanan_5' => 0, // PUKAT
                'asetperikanan_6' => 0, // JALA
            ];

            if ($punyaPerikanan) {
                // Keluarga ini punya aktivitas perikanan → tentukan jenis dan skala realistis

                // 1. Keramba (budidaya air tawar/payau, biasanya skala kecil-menengah)
                if (mt_rand(1, 100) <= 35) { // 35% dari yang punya perikanan
                    $jumlahKeramba = $this->randomJumlah(1, 20, $ekonomi); // biasanya 1-20 keramba
                    $row['asetperikanan_1'] = $jumlahKeramba;
                }

                // 2. Tambak (udang/ikan bandeng, biasanya lebih besar, butuh modal)
                if (mt_rand(1, 100) <= 20) { // lebih jarang
                    $luasTambak = $this->randomJumlah(0.5, 5, $ekonomi); // dalam hektar, bisa desimal
                    $row['asetperikanan_2'] = $luasTambak;
                }

                // 3. Jermal (alat tangkap tetap di laut, khas daerah tertentu, jarang)
                if (mt_rand(1, 100) <= 8) {
                    $row['asetperikanan_3'] = $this->randomJumlah(1, 4, $ekonomi);
                }

                // 4. Pancing (alat tangkap sederhana, sangat umum bagi nelayan kecil)
                if (mt_rand(1, 100) <= 70) {
                    $row['asetperikanan_4'] = $this->randomJumlah(1, 15, $ekonomi); // 1-15 set pancing
                }

                // 5. Pukat (jaring tarik, biasanya kelompok, butuh modal lebih)
                if (mt_rand(1, 100) <= 25) {
                    $row['asetperikanan_5'] = $this->randomJumlah(1, 6, $ekonomi);
                }

                // 6. Jala (jaring lempar, umum untuk nelayan tradisional)
                if (mt_rand(1, 100) <= 60) {
                    $row['asetperikanan_6'] = $this->randomJumlah(1, 10, $ekonomi);
                }

                // Bonus: keluarga kaya bisa punya kombinasi lebih banyak
                if ($ekonomi >= 3 && mt_rand(1, 100) <= 40) {
                    // Tambah satu jenis alat/budidaya lagi secara acak
                    $extra = mt_rand(1, 6);
                    $row["asetperikanan_$extra"] += $this->randomJumlah(1, 8, $ekonomi);
                }
            }
            // Jika tidak punya perikanan, semua tetap 0 (sudah diinisialisasi)

            $data[] = $row;

            // Insert per batch
            if (count($data) >= $batchSize) {
                DB::table('data_asetperikanan')->insert($data);
                $this->command->info("Inserted " . count($data) . " data aset perikanan.");
                $data = [];
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            DB::table('data_asetperikanan')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data aset perikanan.");
        }

        $this->command->info("Seeder DataAsetPerikanan selesai! Total {$totalKK} keluarga memiliki data aset perikanan yang realistis dan bervariasi.");
    }

    /**
     * Helper: Base chance dengan bonus berdasarkan tingkat ekonomi
     */
    private function baseChance(int $base, int $ekonomiLevel): int
    {
        $bonus = ($ekonomiLevel - 1) * 10; // level 1: +0, level 2: +10, dst.
        return min($base + $bonus, 90); // maksimal 90% agar tetap ada variasi
    }

    /**
     * Helper: Generate jumlah realistis (bisa integer atau float untuk luas tambak)
     * minBase dan maxBase akan dikalikan dengan faktor ekonomi
     */
    private function randomJumlah(float $minBase, float $maxBase, int $ekonomiLevel): float
    {
        $faktor = [1 => 1.0, 2 => 1.5, 3 => 2.5, 4 => 4.0][$ekonomiLevel];

        $min = $minBase * $faktor * 0.7; // sedikit variasi ke bawah
        $max = $maxBase * $faktor * 1.3; // sedikit variasi ke atas

        // Untuk tambak (hektar), boleh desimal
        if ($minBase < 1) {
            return round(mt_rand($min * 100, $max * 100) / 100, 2); // 2 desimal
        }

        return mt_rand((int)ceil($min), (int)floor($max));
    }
}