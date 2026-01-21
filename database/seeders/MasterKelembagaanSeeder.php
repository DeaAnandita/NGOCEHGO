<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKelembagaanSeeder extends Seeder
{
    public function run(): void
    {
        // ================= JABATAN =================
        DB::table('master_jabatan_kelembagaan')->insert([
            ['kdjabatan' => '1', 'jabatan' => 'Ketua'],
            ['kdjabatan' => '2', 'jabatan' => 'Wakil Ketua'],
            ['kdjabatan' => '3', 'jabatan' => 'Sekretaris'],
            ['kdjabatan' => '4', 'jabatan' => 'Bendahara'],
            ['kdjabatan' => '5', 'jabatan' => 'Koordinator'],
            ['kdjabatan' => '6', 'jabatan' => 'Anggota'],
        ]);

        // ================= UNIT =================
        DB::table('master_unit_kelembagaan')->insert([
            ['kdunit' => '1', 'nama_unit' => 'Sekretariat'],
            ['kdunit' => '2', 'nama_unit' => 'Keuangan'],
            ['kdunit' => '3', 'nama_unit' => 'Program'],
            ['kdunit' => '4', 'nama_unit' => 'Humas'],
            ['kdunit' => '5', 'nama_unit' => 'Pendidikan'],
        ]);

        // ================= PERIODE (AWAL) =================
        DB::table('master_periode_kelembagaan')->insert([
            ['kdperiode' => '2024', 'tahun_awal' => '2024'],
            ['kdperiode' => '2026', 'tahun_awal' => '2026'],
        ]);

        // ================= STATUS PENGURUS =================
        DB::table('master_status_pengurus_kelembagaan')->insert([
            ['kdstatus' => '1', 'status_pengurus' => 'Aktif'],
            ['kdstatus' => '2', 'status_pengurus' => 'Nonaktif'],
            ['kdstatus' => '3', 'status_pengurus' => 'Mengundurkan Diri'],
            ['kdstatus' => '4', 'status_pengurus' => 'Diberhentikan'],
        ]);

        // ================= JENIS SK =================
        DB::table('master_jenis_sk_kelembagaan')->insert([
            ['kdjenissk' => '1', 'jenis_sk' => 'SK Pengangkatan'],
            ['kdjenissk' => '2', 'jenis_sk' => 'SK Pergantian'],
            ['kdjenissk' => '3', 'jenis_sk' => 'SK Pemberhentian'],
        ]);

        DB::table('master_periode_kelembagaan_akhir')->insert([
            ['kdperiode' => '2024', 'akhir' => '2026'],
            ['kdperiode' => '2026', 'akhir' => '2028'],
        ]);
    }
}
