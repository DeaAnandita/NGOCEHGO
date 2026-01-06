<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetTernakSeeder extends Seeder
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
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data aset ternak yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data aset ternak untuk {$totalKK} keluarga...");

        $batchSize = 500;
        $data = [];

        foreach ($kkList as $no_kk) {
            // Tentukan tingkat ekonomi secara acak (sama seperti seeder aset keluarga & perikanan)
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

            // Probabilitas dasar memiliki ternak (di pedesaan Indonesia cukup umum ~40-60%)
            $chancePunyaTernak = $this->baseChance(45, $ekonomi); // base 45% + bonus ekonomi
            $punyaTernak = mt_rand(1, 100) <= $chancePunyaTernak;

            // Inisialisasi semua aset ternak ke 0
            $row = [
                'no_kk' => $no_kk,
                'asetternak_1'  => 0, // SAPI
                'asetternak_2'  => 0, // KERBAU
                'asetternak_3'  => 0, // BABI
                'asetternak_4'  => 0, // AYAM KAMPUNG
                'asetternak_5'  => 0, // AYAM BROILER
                'asetternak_6'  => 0, // BEBEK
                'asetternak_7'  => 0, // KUDA
                'asetternak_8'  => 0, // KAMBING
                'asetternak_9'  => 0, // DOMBA
                'asetternak_10' => 0, // ANGSA
                'asetternak_11' => 0, // BURUNG PUYUH
                'asetternak_12' => 0, // KELINCI
                'asetternak_13' => 0, // BURUNG WALET
                'asetternak_14' => 0, // ANJING
                'asetternak_15' => 0, // KUCING
            ];

            if ($punyaTernak) {
                // Jenis ternak yang paling umum di pedesaan Indonesia

                // 1. Ayam kampung (paling umum, hampir semua peternak punya)
                if (mt_rand(1, 100) <= 85) {
                    $row['asetternak_4'] = $this->randomJumlah(3, 30, $ekonomi); // 3-30 ekor
                }

                // 2. Bebek
                if (mt_rand(1, 100) <= 40) {
                    $row['asetternak_6'] = $this->randomJumlah(2, 25, $ekonomi);
                }

                // 3. Kambing (umum di banyak daerah)
                if (mt_rand(1, 100) <= 45) {
                    $row['asetternak_8'] = $this->randomJumlah(1, 12, $ekonomi);
                }

                // 4. Domba (mirip kambing, tapi sedikit lebih jarang)
                if (mt_rand(1, 100) <= 20) {
                    $row['asetternak_9'] = $this->randomJumlah(1, 10, $ekonomi);
                }

                // 5. Sapi (butuh modal & lahan lebih besar → lebih banyak di keluarga menengah atas)
                if (mt_rand(1, 100) <= $this->baseChance(15, $ekonomi)) {
                    $row['asetternak_1'] = $this->randomJumlah(1, 5, $ekonomi); // biasanya 1-5 ekor
                }

                // 6. Kerbau (mirip sapi, lebih jarang sekarang)
                if (mt_rand(1, 100) <= $this->baseChance(8, $ekonomi)) {
                    $row['asetternak_2'] = $this->randomJumlah(1, 4, $ekonomi);
                }

                // 7. Ayam broiler (skala komersial kecil-menengah)
                if (mt_rand(1, 100) <= 25) {
                    $row['asetternak_5'] = $this->randomJumlah(50, 500, $ekonomi); // skala lebih besar
                }

                // 8. Burung puyuh (kadang usaha sampingan)
                if (mt_rand(1, 100) <= 12) {
                    $row['asetternak_11'] = $this->randomJumlah(20, 200, $ekonomi);
                }

                // 9. Kelinci (ternak hobi/usaha kecil)
                if (mt_rand(1, 100) <= 15) {
                    $row['asetternak_12'] = $this->randomJumlah(2, 20, $ekonomi);
                }

                // 10. Angsa
                if (mt_rand(1, 100) <= 10) {
                    $row['asetternak_10'] = $this->randomJumlah(2, 15, $ekonomi);
                }

                // 11. Kuda (sangat jarang, biasanya daerah tertentu)
                if (mt_rand(1, 100) <= $this->baseChance(3, $ekonomi)) {
                    $row['asetternak_7'] = $this->randomJumlah(1, 3, $ekonomi);
                }

                // 12. Babi (hanya di daerah non-muslim mayoritas, kita buat jarang)
                if (mt_rand(1, 100) <= 5) {
                    $row['asetternak_3'] = $this->randomJumlah(1, 10, $ekonomi);
                }

                // 13. Burung walet (usaha sarang walet → butuh modal besar & lokasi khusus)
                if (mt_rand(1, 100) <= $this->baseChance(2, $ekonomi)) {
                    $row['asetternak_13'] = $this->randomJumlah(1, 4, $ekonomi); // jumlah gedung
                }

                // 14-15. Anjing & Kucing → biasanya peliharaan, bukan ternak ekonomi
                // Tetap beri kemungkinan kecil memiliki (bukan untuk dijual)
                if (mt_rand(1, 100) <= 30) {
                    $row['asetternak_14'] = $this->randomJumlah(0, 3, $ekonomi);
                }
                if (mt_rand(1, 100) <= 50) {
                    $row['asetternak_15'] = $this->randomJumlah(1, 5, $ekonomi);
                }

                // Bonus kombinasi untuk keluarga kaya/menengah atas
                if ($ekonomi >= 3 && mt_rand(1, 100) <= 50) {
                    $extra = mt_rand(1, 13);
                    $row["asetternak_$extra"] += $this->randomJumlah(1, 8, $ekonomi);
                }
            } else {
                // Tidak punya ternak ekonomi, tapi tetap mungkin punya peliharaan
                if (mt_rand(1, 100) <= 25) {
                    $row['asetternak_14'] = $this->randomJumlah(0, 2, $ekonomi); // anjing
                }
                if (mt_rand(1, 100) <= 45) {
                    $row['asetternak_15'] = $this->randomJumlah(1, 4, $ekonomi); // kucing
                }
            }

            $data[] = $row;

            // Batch insert
            if (count($data) >= $batchSize) {
                DB::table('data_asetternak')->insert($data);
                $this->command->info("Inserted " . count($data) . " data aset ternak.");
                $data = [];
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            DB::table('data_asetternak')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data aset ternak.");
        }

        $this->command->info("Seeder DataAsetTernak selesai! Total {$totalKK} keluarga memiliki data aset ternak yang realistis dan bervariasi.");
    }

    /**
     * Base chance dengan bonus ekonomi
     */
    private function baseChance(int $base, int $ekonomiLevel): int
    {
        $bonus = ($ekonomiLevel - 1) * 12;
        return min($base + $bonus, 95);
    }

    /**
     * Generate jumlah ternak realistis sesuai tingkat ekonomi
     */
    private function randomJumlah(int $minBase, int $maxBase, int $ekonomiLevel): int
    {
        $faktor = [1 => 1.0, 2 => 1.4, 3 => 2.2, 4 => 3.5][$ekonomiLevel];

        $min = (int)ceil($minBase * $faktor * 0.8);
        $max = (int)floor($maxBase * $faktor * 1.2);

        $min = max($min, 1); // minimal 1 jika dipilih
        return mt_rand($min, $max);
    }
}