<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class KelembagaanKeuanganSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        DB::beginTransaction();

        try {

            // ===============================
            // MASTER IDS
            // ===============================
            $jenisKeputusan = DB::table('master_jenis_keputusan')->pluck('kdjenis')->toArray();
            $unit = DB::table('master_unit_keputusan')->pluck('kdunit')->toArray();
            $periode = DB::table('master_periode_kelembagaan')->pluck('kdperiode')->toArray();
            $statusKeputusan = DB::table('master_status_keputusan')->pluck('kdstatus')->toArray();
            $metode = DB::table('master_metode_keputusan')->pluck('kdmetode')->toArray();
            $jabatan = DB::table('master_jabatan_kelembagaan')->pluck('kdjabatan')->toArray();

            $jenisKegiatan = DB::table('master_jenis_kegiatan')->pluck('kdjenis')->toArray();
            $statusKegiatan = DB::table('master_status_kegiatan')->pluck('kdstatus')->toArray();
            $sumberDana = DB::table('master_sumber_dana')->pluck('kdsumber')->toArray();

            $jenisAgenda = DB::table('master_jenis_agenda')->pluck('kdjenis')->toArray();
            $statusAgenda = DB::table('master_status_agenda')->pluck('kdstatus')->toArray();
            $tempatAgenda = DB::table('master_tempat_agenda')->pluck('kdtempat')->toArray();

            if (empty($unit) || empty($periode) || empty($sumberDana)) {
                $this->command->error("Master data belum lengkap.");
                return;
            }

            // ===============================
            // 1. KEPUTUSAN
            // ===============================
            $keputusanIDs = [];

            for ($i = 1; $i <= 10; $i++) {
                $id = DB::table('keputusan')->insertGetId([
                    'kdjenis' => $faker->randomElement($jenisKeputusan),
                    'kdunit' => $faker->randomElement($unit),
                    'kdperiode' => $faker->randomElement($periode),
                    'kdstatus' => $faker->randomElement($statusKeputusan),
                    'kdmetode' => $faker->randomElement($metode),
                    'kdjabatan' => $faker->randomElement($jabatan),
                    'nomor_sk' => "SK-$i/ORG/2024",
                    'judul_keputusan' => "Keputusan Organisasi $i",
                    'tanggal_keputusan' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $keputusanIDs[] = $id;
            }

            // ===============================
            // 2. ANGGARAN
            // ===============================
            $anggaranIDs = [];

            foreach ($unit as $u) {
                foreach (array_slice($periode, 0, 2) as $p) {

                    $id = DB::table('anggaran_kelembagaan')->insertGetId([
                        'kdunit' => $u,
                        'kdperiode' => $p,
                        'kdsumber' => $faker->randomElement($sumberDana),
                        'total_anggaran' => $faker->numberBetween(300, 800) * 1000000,
                        'keterangan' => 'Anggaran Tahunan',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $anggaranIDs[] = $id;
                }
            }

            // ===============================
            // 3. KEGIATAN
            // ===============================
            $kegiatanIDs = [];

            for ($i = 1; $i <= 40; $i++) {
                $kegiatanIDs[] = DB::table('kegiatan')->insertGetId([
                    'keputusan_id' => $faker->randomElement($keputusanIDs),
                    'kdjenis' => $faker->randomElement($jenisKegiatan),
                    'kdunit' => $faker->randomElement($unit),
                    'kdperiode' => $faker->randomElement($periode),
                    'kdstatus' => $faker->randomElement($statusKegiatan),
                    'kdsumber' => $faker->randomElement($sumberDana),
                    'nama_kegiatan' => "Kegiatan " . $faker->words(3, true),
                    'lokasi' => $faker->city,
                    'pagu_anggaran' => 0, // pakai kegiatan_anggaran
                    'tgl_mulai' => now(),
                    'tgl_selesai' => now()->addDays(3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ===============================
            // 4. KEGIATAN ANGGARAN
            // ===============================
            foreach ($kegiatanIDs as $k) {
                DB::table('kegiatan_anggaran')->insert([
                    'anggaran_id' => $faker->randomElement($anggaranIDs),
                    'kegiatan_id' => $k,
                    'kdsumber' => $faker->randomElement($sumberDana),
                    'nilai_anggaran' => $faker->numberBetween(10, 100) * 1000000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ===============================
            // 5. PENCAIRAN
            // ===============================
            $pencairanIDs = [];

            foreach ($kegiatanIDs as $k) {
                $pencairanIDs[] = DB::table('pencairan_dana')->insertGetId([
                    'kegiatan_id' => $k,
                    'tanggal_cair' => now(),
                    'jumlah' => $faker->numberBetween(5, 50) * 1000000,
                    'no_sp2d' => 'SP2D-' . $faker->randomNumber(5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ===============================
            // 6. REALISASI
            // ===============================
            foreach ($pencairanIDs as $p) {
                for ($i = 1; $i <= 3; $i++) {
                    DB::table('realisasi_pengeluaran')->insert([
                        'pencairan_id' => $p,
                        'tanggal' => now(),
                        'uraian' => 'Belanja ' . $faker->word,
                        'jumlah' => $faker->numberBetween(1, 10) * 1000000,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // ===============================
            // 7. LPJ
            // ===============================
            foreach ($kegiatanIDs as $k) {

                $total = DB::table('kegiatan_anggaran')
                    ->where('kegiatan_id', $k)
                    ->sum('nilai_anggaran');

                $realisasi = DB::table('pencairan_dana')
                    ->where('kegiatan_id', $k)
                    ->sum('jumlah');

                DB::table('lpj_kegiatan')->insert([
                    'kegiatan_id' => $k,
                    'total_anggaran' => $total,
                    'total_realisasi' => $realisasi,
                    'sisa_anggaran' => $total - $realisasi,
                    'status' => $faker->randomElement([0, 1]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ===============================
            // 8. AGENDA
            // ===============================
            for ($i = 1; $i <= 30; $i++) {
                DB::table('agenda_kelembagaan')->insert([
                    'kdjenis' => $faker->randomElement($jenisAgenda),
                    'kdunit' => $faker->randomElement($unit),
                    'kdstatus' => $faker->randomElement($statusAgenda),
                    'kdtempat' => $faker->randomElement($tempatAgenda),
                    'kdperiode' => $faker->randomElement($periode),
                    'judul_agenda' => 'Agenda ' . $faker->words(3, true),
                    'uraian_agenda' => $faker->sentence,
                    'tanggal' => now(),
                    'jam_mulai' => '09:00',
                    'jam_selesai' => '12:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            $this->command->info("Seeder Kelembagaan & Keuangan berhasil.");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error($e->getMessage());
        }
    }
}
