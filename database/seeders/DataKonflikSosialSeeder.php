<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DataKeluarga;

class DataKonflikSosialSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // Ambil semua no_kk dari tabel data_keluarga
        $keluargas = DataKeluarga::pluck('no_kk');

        // Jika belum ada data keluarga, tambahkan dummy
        if ($keluargas->isEmpty()) {
            DB::table('data_keluarga')->insert([
                [
                    'no_kk' => '3374010000000001',
                    'keluarga_kepalakeluarga' => 'Budi Santoso',
                ],
                [
                    'no_kk' => '3374010000000002',
                    'keluarga_kepalakeluarga' => 'Siti Aminah',
                ],
            ]);
            $keluargas = collect(['3374010000000001', '3374010000000002']);
        }

        // Buat data konflik sosial untuk setiap keluarga
        foreach ($keluargas as $no_kk) {
            $data = ['no_kk' => $no_kk];

            // Isi 32 kolom konflik sosial dengan nilai acak 0–2
            for ($i = 1; $i <= 32; $i++) {
                $data["konfliksosial_$i"] = rand(0, 2);
            }

            DB::table('data_konfliksosial')->insert($data);
        }
    }
}
