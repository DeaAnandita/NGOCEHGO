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
        // Ambil no_kk acak dari data_keluarga yang sudah ada
        $existingKK = DB::table('data_keluarga')
            ->inRandomOrder()
            ->limit(500) // Ubah jadi 1200 jika mau semua
            ->pluck('no_kk')
            ->toArray();

        // Jika tabel data_keluarga kosong (misal seeder ini jalan duluan)
        if (empty($existingKK)) {
            $this->command->warn('Tabel data_keluarga kosong. Membuat 500 dummy no_kk sementara.');
            $existingKK = [];
            for ($i = 1; $i <= 500; $i++) {
                $existingKK[] = '3319012001' . str_pad($i, 6, '0', STR_PAD_LEFT);
            }
        }

        shuffle($existingKK);

        $data = [];
        $batchSize = 200;

        foreach ($existingKK as $noKK) {
            $pendapatan = $this->randPendapatanBulanan();

            $data[] = [
                'no_kk' => $noKK,

                // 61: Uang saku harian
                'sejahterakeluarga_61' => $this->randUangSakuHarian(),

                // 62: Rokok per hari
                'sejahterakeluarga_62' => rand(0, 4) . ' bungkus',

                // 63: Minum kopi/teh per hari
                'sejahterakeluarga_63' => rand(0, 5) . ' kali',

                // 64: Lama hiburan (TV/internet) per hari
                'sejahterakeluarga_64' => rand(0, 8) . ' jam',

                // 65: Pulsa & internet per bulan
                'sejahterakeluarga_65' => $this->randRupiah(50000, 500000, 10000),

                // 66: Pendapatan bulanan
                'sejahterakeluarga_66' => $pendapatan,

                // 67: Pengeluaran rutin bulanan
                'sejahterakeluarga_67' => $this->randPengeluaranBulanan($pendapatan),

                // 68: Belanja kebutuhan pokok
                'sejahterakeluarga_68' => $this->randRupiah(800000, 4000000, 50000),
            ];

            // Insert per batch agar aman memory
            if (count($data) >= $batchSize) {
                DB::table('data_sejahterakeluarga')->insert($data);
                $data = []; // Kosongkan untuk batch berikutnya
            }
        }

        // Insert sisa data yang belum ke-insert
        if (!empty($data)) {
            DB::table('data_sejahterakeluarga')->insert($data);
        }

        $this->command->info('Seeder DataSejahteraKeluarga selesai! ' . count($existingKK) . ' data kesejahteraan keluarga berhasil dimasukkan.');
    }

    // ==================== PRIVATE METHODS ====================

    private function randRupiah(int $min, int $max, int $kelipatan = 50000): int
    {
        $steps = (int) ceil(($max - $min) / $kelipatan);
        return $min + (rand(0, $steps) * $kelipatan);
    }

    private function randUangSakuHarian(): int
    {
        return $this->randRupiah(10000, 100000, 5000);
    }

    private function randPendapatanBulanan(): int
    {
        // 2 juta - 15 juta, kelipatan 100 ribu
        return $this->randRupiah(2000000, 15000000, 100000);
    }

    private function randPengeluaranBulanan(int $pendapatan): int
    {
        $persen = rand(60, 95) / 100; // 60-95% dari pendapatan
        $pengeluaran = (int) ($pendapatan * $persen);

        // Pastikan tidak kurang dari 1 juta dan tidak lebih dari pendapatan
        return max(1000000, min($pengeluaran, $pendapatan));
    }
}