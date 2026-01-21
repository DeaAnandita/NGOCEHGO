<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengurusKelembagaan;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PengurusKelembagaanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil semua ID dari tabel master (sekali saja)
        $jabatan = DB::table('master_jabatan_kelembagaan')->pluck('kdjabatan')->toArray();
        $unit = DB::table('master_unit_kelembagaan')->pluck('kdunit')->toArray();
        $periode = DB::table('master_periode_kelembagaan')->pluck('kdperiode')->toArray();
        $periodeAkhir = DB::table('master_periode_kelembagaan_akhir')->pluck('kdperiode')->toArray();
        $status = DB::table('master_status_pengurus_kelembagaan')->pluck('kdstatus')->toArray();
        $jenisSk = DB::table('master_jenis_sk_kelembagaan')->pluck('kdjenissk')->toArray();

        // Validasi: kalau salah satu master kosong → hentikan
        if (
            empty($jabatan) || empty($unit) || empty($periode) ||
            empty($periodeAkhir) || empty($status) || empty($jenisSk)
        ) {
            $this->command->error("Tabel master masih kosong. Jalankan seeder master dulu.");
            return;
        }

        for ($i = 1; $i <= 100; $i++) {

            PengurusKelembagaan::create([
                'nomor_induk' => $faker->unique()->numerify('################'),
                'nama_lengkap' => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '2003-01-01'),
                'alamat' => $faker->address,
                'no_hp' => '08' . $faker->numerify('##########'),
                'email' => $faker->unique()->safeEmail,

                // Foreign key aman
                'kdjabatan' => $faker->randomElement($jabatan),
                'kdunit' => $faker->randomElement($unit),
                'kdperiode' => $faker->randomElement($periode),
                'kdperiode_akhir' => $faker->randomElement($periodeAkhir),
                'kdstatus' => $faker->randomElement($status),
                'kdjenissk' => $faker->randomElement($jenisSk),

                'no_sk' => 'SK-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '/ORG/2024',
                'tanggal_sk' => now(),

                'foto' => null,
                'tanda_tangan' => null,
                'keterangan' => 'Pengurus periode 2024–2026',
            ]);
        }
    }
}
