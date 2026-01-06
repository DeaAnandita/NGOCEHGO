<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataBangunKeluargaSeeder extends Seeder
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
            $this->command->warn('Tabel data_keluarga kosong! Tidak ada data bangun keluarga yang bisa dibuat.');
            return;
        }

        $totalKK = count($kkList);
        $this->command->info("Membuat data bangun keluarga untuk {$totalKK} keluarga...");

        $batchSize = 500;
        $data = [];

        foreach ($kkList as $no_kk) {
            // Tentukan tingkat kesejahteraan keluarga secara realistis untuk desa
            // 1 = Rentan (miskin), 2 = Berkembang (menengah), 3 = Sejahtera
            $rand = mt_rand(1, 100);
            if ($rand <= 20) {
                $tingkat = 1; // 20% Rentan
            } elseif ($rand <= 70) {
                $tingkat = 2; // 50% Berkembang
            } else {
                $tingkat = 3; // 30% Sejahtera
            }

            $data[] = [
                'no_kk' => $no_kk,

                /* ===================== KEBUTUHAN DASAR ===================== */
                'bangunkeluarga_1'  => $this->prob(85 + 10 * $tingkat), // Beli pakaian baru (sangat umum di sejahtera)
                'bangunkeluarga_2'  => $this->prob(95 + 4 * $tingkat),  // Makan ≥2x sehari (hampir semua)
                'bangunkeluarga_3'  => $this->prob(70 + 15 * $tingkat), // Berobat ke faskes
                'bangunkeluarga_4'  => $this->prob(90 + 8 * $tingkat),  // Pakaian bersih & layak
                'bangunkeluarga_5'  => $this->prob(60 + 20 * $tingkat), // Makan protein (daging/ikan/telur/tempe)
                'bangunkeluarga_6'  => $this->prob(95),                // Menjalankan ibadah (sangat tinggi di desa)

                /* ===================== PERENCANAAN & EKONOMI ===================== */
                'bangunkeluarga_7'  => $this->prob(30 + 30 * $tingkat), // KB aktif (PUS dengan ≥2 anak)
                'bangunkeluarga_8'  => $this->prob(15 + 30 * $tingkat), // Punya tabungan (uang/emas/deposito)
                'bangunkeluarga_9'  => $this->prob(80 + 10 * $tingkat), // Kebiasaan komunikasi antar anggota
                'bangunkeluarga_10' => $this->prob(50 + 20 * $tingkat), // Ikut kegiatan sosial RT
                'bangunkeluarga_11' => $this->prob(60 + 20 * $tingkat), // Akses informasi (TV/radio/internet)
                'bangunkeluarga_12' => $this->prob(10 + 20 * $tingkat), // Ada pengurus organisasi masyarakat

                /* ===================== KEGIATAN KELUARGA ===================== */
                'bangunkeluarga_13' => $this->prob(70 + 15 * $tingkat), // Balita ikut posyandu
                'bangunkeluarga_14' => $this->prob(40 + 25 * $tingkat), // Balita ikut BKB
                'bangunkeluarga_15' => $this->prob(30 + 25 * $tingkat), // Remaja ikut BKR
                'bangunkeluarga_16' => $this->prob(20 + 20 * $tingkat), // Remaja ikut PIK-R/M
                'bangunkeluarga_17' => $this->prob(50 + 20 * $tingkat), // Lansia ikut BKL
                'bangunkeluarga_18' => $this->prob(25 + 25 * $tingkat), // Ikut UPPKS (usaha peningkatan pendapatan)

                /* ===================== MASALAH SOSIAL ===================== */
                // Masalah sosial lebih tinggi pada keluarga rentan
                'bangunkeluarga_19' => $this->prob(2 + 8 * (4 - $tingkat)), // Ada yang mengemis
                'bangunkeluarga_20' => $this->prob(1 + 5 * (4 - $tingkat)), // Tidur di jalan
                'bangunkeluarga_21' => $this->prob(10 + 15 * (4 - $tingkat)), // Lansia tidak produktif
                'bangunkeluarga_22' => $this->prob(2 + 8 * (4 - $tingkat)), // Anak mengemis
                'bangunkeluarga_23' => $this->prob(3 + 10 * (4 - $tingkat)), // Anak/pengamen
                'bangunkeluarga_24' => $this->prob(3 + 8 * (4 - $tingkat)), // Gila/stres
                'bangunkeluarga_25' => $this->prob(8 + 10 * (4 - $tingkat)), // Cacat fisik
                'bangunkeluarga_26' => $this->prob(2 + 5 * (4 - $tingkat)), // Cacat mental
                'bangunkeluarga_27' => $this->prob(5 + 8 * (4 - $tingkat)), // Kelainan kulit
                'bangunkeluarga_28' => $this->prob(3 + 10 * (4 - $tingkat)), // Anggota jadi pengamen
                'bangunkeluarga_29' => $this->prob(10 + 15 * (4 - $tingkat)), // Yatim/piatu
                'bangunkeluarga_30' => $this->prob(12 + 15 * (4 - $tingkat)), // Keluarga janda/duda
                'bangunkeluarga_31' => $this->prob(8 + 10 * (4 - $tingkat)), // Keluarga duda

                /* ===================== LINGKUNGAN TINGGAL ===================== */
                'bangunkeluarga_32' => $this->prob(5 + 15 * (4 - $tingkat)), // Tinggal di bantaran sungai
                'bangunkeluarga_33' => $this->prob(3 + 10 * (4 - $tingkat)), // Jalur hijau
                'bangunkeluarga_34' => $this->prob(2 + 8 * (4 - $tingkat)),  // Dekat rel KA
                'bangunkeluarga_35' => $this->prob(3 + 8 * (4 - $tingkat)),  // Dekat SUTET
                'bangunkeluarga_36' => $this->prob(15 + 20 * (4 - $tingkat)), // Kawasan kumuh/padat

                /* ===================== EKONOMI & KERJA ===================== */
                'bangunkeluarga_37' => $this->prob(20 + 30 * (4 - $tingkat)), // Ada yang menganggur
                'bangunkeluarga_38' => $this->prob(10 + 25 * (4 - $tingkat)), // Anak bantu cari nafkah
                'bangunkeluarga_39' => $this->prob(15 + 20 * (4 - $tingkat)), // Kepala keluarga perempuan
                'bangunkeluarga_40' => $this->prob(2 + 8 * (4 - $tingkat)),   // Eks narapidana

                /* ===================== KERAWANAN WILAYAH ===================== */
                // Asumsi desa memiliki beberapa risiko bencana sedang
                'bangunkeluarga_41' => $this->prob(30), // Rawan banjir
                'bangunkeluarga_42' => $this->prob(5),  // Rawan tsunami (jarang di desa daratan)
                'bangunkeluarga_43' => $this->prob(8),  // Rawan gunung meletus
                'bangunkeluarga_44' => $this->prob(40), // Rawan gempa
                'bangunkeluarga_45' => $this->prob(25), // Rawan longsor
                'bangunkeluarga_46' => $this->prob(20), // Rawan kebakaran
                'bangunkeluarga_47' => $this->prob(10), // Rawan kelaparan
                'bangunkeluarga_48' => $this->prob(15), // Rawan air bersih
                'bangunkeluarga_49' => $this->prob(12), // Rawan kekeringan
                'bangunkeluarga_50' => $this->prob(18), // Rawan gagal panen
                'bangunkeluarga_51' => $this->prob(10), // Kawasan kering/tandus
            ];

            // Insert batch
            if (count($data) >= $batchSize) {
                DB::table('data_bangunkeluarga')->insert($data);
                $this->command->info("Inserted " . count($data) . " data bangun keluarga.");
                $data = [];
            }
        }

        // Insert sisa data
        if (!empty($data)) {
            DB::table('data_bangunkeluarga')->insert($data);
            $this->command->info("Inserted sisa " . count($data) . " data bangun keluarga.");
        }

        $this->command->info("Seeder DataBangunKeluarga selesai! Total {$totalKK} keluarga memiliki data yang realistis untuk konteks desa.");
    }

    /**
     * Helper: Mengembalikan 1 (YA) jika lolos probabilitas, 2 (TIDAK) jika tidak
     *
     * @param int $chance Persentase peluang (1-99)
     * @return int
     */
    private function prob(int $chance): int
    {
        $chance = max(1, min(99, $chance)); // Batasi 1-99 agar selalu ada variasi
        return mt_rand(1, 100) <= $chance ? 1 : 2;
    }
}