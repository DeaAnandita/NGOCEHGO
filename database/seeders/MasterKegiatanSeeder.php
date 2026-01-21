<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // ============================
        // MASTER JENIS KEGIATAN
        // ============================
        DB::table('master_jenis_kegiatan')->insert([
            ['kdjenis' => 1, 'jenis_kegiatan' => 'Pembangunan'],
            ['kdjenis' => 2, 'jenis_kegiatan' => 'Pemberdayaan'],
            ['kdjenis' => 3, 'jenis_kegiatan' => 'Pelatihan'],
            ['kdjenis' => 4, 'jenis_kegiatan' => 'Bantuan Sosial'],
            ['kdjenis' => 5, 'jenis_kegiatan' => 'Operasional Desa'],
            ['kdjenis' => 6, 'jenis_kegiatan' => 'Kegiatan Pemuda'],
            ['kdjenis' => 7, 'jenis_kegiatan' => 'Kesehatan'],
            ['kdjenis' => 8, 'jenis_kegiatan' => 'Pendidikan'],
        ]);

        // ============================
        // MASTER STATUS KEGIATAN
        // ============================
        DB::table('master_status_kegiatan')->insert([
            ['kdstatus' => 1, 'status_kegiatan' => 'Direncanakan'],
            ['kdstatus' => 2, 'status_kegiatan' => 'Berjalan'],
            ['kdstatus' => 3, 'status_kegiatan' => 'Selesai'],
            ['kdstatus' => 4, 'status_kegiatan' => 'Dibatalkan'],
        ]);

        // ============================
        // MASTER SUMBER DANA
        // ============================
        DB::table('master_sumber_dana')->insert([
            ['kdsumber' => 1, 'sumber_dana' => 'Dana Desa'],
            ['kdsumber' => 2, 'sumber_dana' => 'Alokasi Dana Desa'],
            ['kdsumber' => 3, 'sumber_dana' => 'Bantuan Provinsi'],
            ['kdsumber' => 4, 'sumber_dana' => 'Bantuan Kabupaten'],
            ['kdsumber' => 5, 'sumber_dana' => 'Swadaya Masyarakat'],
            ['kdsumber' => 6, 'sumber_dana' => 'CSR'],
            ['kdsumber' => 7, 'sumber_dana' => 'BUMDes'],
        ]);
    }
    }

