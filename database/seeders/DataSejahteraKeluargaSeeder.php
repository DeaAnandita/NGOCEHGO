<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSejahteraKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua no_kk dari data_keluarga (bukan limit atau random)
        $kkList = DB::table('data_keluarga')
            ->pluck('no_kk')
            ->toArray();

        if (empty($kkList)) {
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data sejahtera keluarga yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data kesejahteraan keluarga realistis untuk {$totalKK} keluarga desa...");

        $batchSize = 500;
        $data = [];

        foreach ($kkList as $no_kk) {
            // Tentukan tingkat kesejahteraan keluarga secara acak tapi realistis untuk desa Indonesia
            // 1 = Pra-Sejahtera / Miskin, 2 = Sejahtera I (menengah bawah), 3 = Sejahtera II (menengah), 4 = Sejahtera III+ (cukup makmur)
            $tingkatRand = mt_rand(1, 100);
            if ($tingkatRand <= 35) {
                $tingkat = 1; // 35% pra-sejahtera / miskin
            } elseif ($tingkatRand <= 75) {
                $tingkat = 2; // 40% Sejahtera I
            } elseif ($tingkatRand <= 95) {
                $tingkat = 3; // 20% Sejahtera II
            } else {
                $tingkat = 4; // 5% Sejahtera III+
            }

            // Pendapatan bulanan realistis desa 2025-2026 (dalam Rupiah)
            $pendapatan = $this->generatePendapatan($tingkat);

            // Pengeluaran rutin bulanan: biasanya 70-95% dari pendapatan, tapi bisa lebih di keluarga miskin
            $pengeluaran = $this->generatePengeluaran($pendapatan, $tingkat);

            // Belanja kebutuhan pokok bulanan
            $belanjaPokok = $this->generateBelanjaPokok($tingkat);

            $row = [
                'no_kk' => $no_kk,

                // 61: Rata-rata uang saku anak untuk sekolah (per hari)
                'sejahterakeluarga_61' => $this->generateUangSakuHarian($tingkat),

                // 62: Kebiasaan merokok (bungkus per hari) - string sesuai master
                'sejahterakeluarga_62' => $this->generateRokok($tingkat),

                // 63: Minum kopi/teh di luar (kedai/warung) - kali per hari
                'sejahterakeluarga_63' => $this->generateMinumKopi($tingkat),

                // 64: Lama hiburan (nonton TV/main HP/internet) - jam per hari
                'sejahterakeluarga_64' => $this->generateHiburan($tingkat) . ' jam',

                // 65: Pengeluaran pulsa & internet per bulan
                'sejahterakeluarga_65' => $this->generatePulsaInternet($tingkat),

                // 66: Pendapatan / penghasilan rata-rata keluarga sebulan
                'sejahterakeluarga_66' => $pendapatan,

                // 67: Pengeluaran rutin keluarga sebulan
                'sejahterakeluarga_67' => $pengeluaran,

                // 68: Rata-rata belanja kebutuhan pokok sebulan
                'sejahterakeluarga_68' => $belanjaPokok,
            ];

            $data[] = $row;

            // Batch insert
            if (count($data) >= $batchSize) {
                DB::table('data_sejahterakeluarga')->insert($data);
                $this->command->info("Inserted " . count($data) . " data sejahtera keluarga.");
                $data = [];
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            DB::table('data_sejahterakeluarga')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data sejahtera keluarga.");
        }

        $this->command->info("Seeder DataSejahteraKeluarga selesai! Total {$totalKK} keluarga memiliki data kesejahteraan yang realistis sesuai kondisi desa Indonesia tahun 2026.");
    }

    // ==================== GENERATOR METHODS ====================

    private function generatePendapatan(int $tingkat): int
    {
        return match ($tingkat) {
            1 => $this->randRupiah(1500000, 3500000, 100000),   // Miskin: 1.5 - 3.5 juta
            2 => $this->randRupiah(3000000, 6000000, 200000),   // Menengah bawah: 3 - 6 juta
            3 => $this->randRupiah(5000000, 10000000, 300000),  // Menengah: 5 - 10 juta
            4 => $this->randRupiah(8000000, 20000000, 500000),  // Makmur: 8 - 20 juta
        };
    }

    private function generatePengeluaran(int $pendapatan, int $tingkat): int
    {
        $persen = match ($tingkat) {
            1 => rand(85, 105) / 100, // Miskin sering defisit
            2 => rand(75, 95) / 100,
            3 => rand(65, 85) / 100,
            4 => rand(50, 75) / 100, // Makmur lebih hemat atau investasi
        };

        $pengeluaran = (int) ($pendapatan * $persen);
        return max(1200000, min($pengeluaran, $pendapatan * 1.1)); // Minimal realistis
    }

    private function generateBelanjaPokok(int $tingkat): int
    {
        return match ($tingkat) {
            1 => $this->randRupiah(800000, 1800000, 50000),
            2 => $this->randRupiah(1500000, 3000000, 100000),
            3 => $this->randRupiah(2500000, 4500000, 100000),
            4 => $this->randRupiah(3500000, 7000000, 200000),
        };
    }

    private function generateUangSakuHarian(int $tingkat): int
    {
        return match ($tingkat) {
            1 => $this->randRupiah(5000, 20000, 1000),
            2 => $this->randRupiah(15000, 40000, 2000),
            3 => $this->randRupiah(30000, 70000, 5000),
            4 => $this->randRupiah(50000, 150000, 10000),
        };
    }

    private function generateRokok(int $tingkat): string
    {
        $probMerokok = match ($tingkat) {
            1 => 75, // Miskin lebih sering merokok
            2 => 65,
            3 => 50,
            4 => 35,
        };

        if (mt_rand(1, 100) > $probMerokok) {
            return '0 bungkus';
        }

        $bungkus = match ($tingkat) {
            1 => rand(1, 3),
            2 => rand(0, 2),
            3 => rand(0, 2),
            4 => rand(0, 1),
        };

        return $bungkus . ' bungkus';
    }

    private function generateMinumKopi(int $tingkat): string
    {
        $probKopi = match ($tingkat) {
            1 => 70,
            2 => 60,
            3 => 50,
            4 => 40,
        };

        if (mt_rand(1, 100) > $probKopi) {
            return '0 kali';
        }

        $kali = match ($tingkat) {
            1 => rand(1, 4),
            2 => rand(1, 3),
            3 => rand(0, 3),
            4 => rand(0, 2),
        };

        return $kali . ' kali';
    }

    private function generateHiburan(int $tingkat): int
    {
        return match ($tingkat) {
            1 => rand(1, 4),
            2 => rand(2, 6),
            3 => rand(3, 7),
            4 => rand(4, 8),
        };
    }

    private function generatePulsaInternet(int $tingkat): int
    {
        return match ($tingkat) {
            1 => $this->randRupiah(30000, 100000, 10000),
            2 => $this->randRupiah(80000, 200000, 20000),
            3 => $this->randRupiah(150000, 400000, 30000),
            4 => $this->randRupiah(300000, 800000, 50000),
        };
    }

    private function randRupiah(int $min, int $max, int $kelipatan = 10000): int
    {
        $steps = (int) ceil(($max - $min) / $kelipatan);
        return $min + (mt_rand(0, $steps) * $kelipatan);
    }
}