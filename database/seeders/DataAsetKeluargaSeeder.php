<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataAsetKeluargaSeeder extends Seeder
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
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data aset yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data aset untuk {$totalKK} keluarga...");

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

            $data[] = [
                'no_kk' => $no_kk,

                // Rumah tangga umum (semakin tinggi ekonomi, semakin mungkin punya)
                'asetkeluarga_1'  => $this->prob(90, $ekonomi), // Tabung gas ≥5.5kg → sangat umum
                'asetkeluarga_2'  => $this->prob(30 + 20 * $ekonomi, $ekonomi), // Laptop/komputer
                'asetkeluarga_3'  => $this->prob(95, $ekonomi), // TV/elektronik → hampir semua punya
                'asetkeluarga_4'  => $this->prob(5 + 15 * $ekonomi, $ekonomi), // AC
                'asetkeluarga_5'  => $this->prob(70 + 15 * $ekonomi, $ekonomi), // Kulkas/water heater
                'asetkeluarga_6'  => $this->prob(5 + 10 * $ekonomi, $ekonomi), // Rumah di tempat lain
                'asetkeluarga_7'  => $this->prob(2 + 5 * $ekonomi, $ekonomi), // Genset/diesel listrik

                // Kendaraan
                'asetkeluarga_8'  => $this->prob(85 + 10 * $ekonomi, $ekonomi), // Sepeda motor → sangat umum
                'asetkeluarga_9'  => $this->prob(5 + 20 * $ekonomi, $ekonomi), // Mobil
                'asetkeluarga_10' => $this->prob(1 + 3 * $ekonomi, $ekonomi), // Perahu motor

                // Aset ekstrem (sangat jarang)
                'asetkeluarga_11' => $this->prob(0.1 * $ekonomi, $ekonomi), // Kapal barang
                'asetkeluarga_12' => $this->prob(0.1 * $ekonomi, $ekonomi), // Kapal penumpang
                'asetkeluarga_13' => $this->prob(0.05 * $ekonomi, $ekonomi), // Kapal pesiar
                'asetkeluarga_14' => 2, // Helikopter → hampir tidak ada
                'asetkeluarga_15' => 2, // Pesawat pribadi → tidak ada

                // Ternak
                'asetkeluarga_16' => $this->prob(10 + 15 * $ekonomi, $ekonomi), // Sapi/kerbau (ternak besar)
                'asetkeluarga_17' => $this->prob(40 + 20 * $ekonomi, $ekonomi), // Kambing/ayam (ternak kecil)

                // Keuangan
                'asetkeluarga_18' => $this->prob(30 + 25 * $ekonomi, $ekonomi), // Emas/perhiasan
                'asetkeluarga_19' => $this->prob(80 + 15 * $ekonomi, $ekonomi), // Rekening tabungan
                'asetkeluarga_20' => $this->prob(2 + 10 * $ekonomi, $ekonomi), // Surat berharga/saham
                'asetkeluarga_21' => $this->prob(1 + 8 * $ekonomi, $ekonomi), // Deposito

                // Properti
                'asetkeluarga_22' => $this->prob(70 + 20 * $ekonomi, $ekonomi), // Sertifikat tanah
                'asetkeluarga_23' => $this->prob(60 + 25 * $ekonomi, $ekonomi), // Sertifikat rumah

                // Usaha & industri
                'asetkeluarga_24' => $this->prob(0.5 + 3 * $ekonomi, $ekonomi), // Industri besar
                'asetkeluarga_25' => $this->prob(1 + 8 * $ekonomi, $ekonomi), // Industri menengah
                'asetkeluarga_26' => $this->prob(10 + 20 * $ekonomi, $ekonomi), // Usaha kecil (warung/toko)

                // Jenis usaha
                'asetkeluarga_27' => $this->prob(3 + 5 * $ekonomi, $ekonomi), // Perikanan
                'asetkeluarga_28' => $this->prob(8 + 10 * $ekonomi, $ekonomi), // Peternakan
                'asetkeluarga_29' => $this->prob(15 + 15 * $ekonomi, $ekonomi), // Perkebunan
                'asetkeluarga_30' => $this->prob(0.5 + 2 * $ekonomi, $ekonomi), // Swalayan/minimarket
                'asetkeluarga_31' => $this->prob(2 + 8 * $ekonomi, $ekonomi), // Kios di swalayan
                'asetkeluarga_32' => $this->prob(20 + 20 * $ekonomi, $ekonomi), // Kios di pasar tradisional
                'asetkeluarga_33' => $this->prob(25 + 20 * $ekonomi, $ekonomi), // Toko/warung di desa
                'asetkeluarga_34' => $this->prob(5 + 10 * $ekonomi, $ekonomi), // Usaha angkutan/transportasi

                // Komunikasi & hiburan
                'asetkeluarga_35' => $this->prob(3 + 15 * $ekonomi, $ekonomi), // Investasi saham
                'asetkeluarga_36' => $this->prob(60 + 20 * $ekonomi, $ekonomi), // Telepon rumah/Telkom
                'asetkeluarga_37' => $this->prob(95, $ekonomi), // HP/smartphone → hampir semua punya
                'asetkeluarga_38' => $this->prob(5, $ekonomi), // HP CDMA (sudah jarang)
                'asetkeluarga_39' => $this->prob(1, $ekonomi), // Wartel → hampir punah
                'asetkeluarga_40' => $this->prob(20 + 20 * $ekonomi, $ekonomi), // Parabola/TV satelit
                'asetkeluarga_41' => $this->prob(30 + 25 * $ekonomi, $ekonomi), // Langganan koran/majalah
                'asetkeluarga_42' => $this->prob(10 + 20 * $ekonomi, $ekonomi), // Akses internet rumah (WiFi)
            ];

            // Insert per batch 500
            if (count($data) >= $batchSize) {
                DB::table('data_asetkeluarga')->insert($data);
                $this->command->info("Inserted " . count($data) . " data aset keluarga.");
                $data = []; // Kosongkan untuk batch berikutnya
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            DB::table('data_asetkeluarga')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data aset keluarga.");
        }

        $this->command->info("Seeder DataAsetKeluarga selesai! Total {$totalKK} keluarga memiliki data aset yang realistis dan bervariasi.");
    }

    /**
     * Helper: Mengembalikan 1 jika lolos probabilitas, 2 jika tidak
     * Probabilitas dihitung lebih tinggi jika tingkat ekonomi tinggi
     */
    private function prob(int $baseChance, int $ekonomiLevel): int
    {
        $chance = $baseChance + ($ekonomiLevel - 1) * 10; // Bonus per level ekonomi
        $chance = min($chance, 99); // Maksimal 99% agar ada variasi
        return mt_rand(1, 100) <= $chance ? 1 : 2;
    }
}