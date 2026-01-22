<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{
    MasterJenisAgenda,
    MasterJenisKeputusan,
    MasterJenisTKD,
    MasterPerolehanTKD,
    MasterPatok,
    MasterPapanNama,
    MasterAparat,
    MasterJenisAgendaUmum,
    MasterJenisKeputusanUmum
};

class MasterAdminUmumSeeder extends Seeder
{
    public function run(): void
    {
        // ============================
        // STATUS HAK TANAH
        // ============================
        DB::table('master_statushak_tanah')->updateOrInsert(['kdstatushaktanah' => 1], ['statushaktanah' => 'Sertifikat Hak Milik']);
        DB::table('master_statushak_tanah')->updateOrInsert(['kdstatushaktanah' => 2], ['statushaktanah' => 'Hak Guna Bangunan']);
        DB::table('master_statushak_tanah')->updateOrInsert(['kdstatushaktanah' => 3], ['statushaktanah' => 'Hak Guna Usaha']);
        DB::table('master_statushak_tanah')->updateOrInsert(['kdstatushaktanah' => 4], ['statushaktanah' => 'Hak Pakai']);
        DB::table('master_statushak_tanah')->updateOrInsert(['kdstatushaktanah' => 5], ['statushaktanah' => 'Tanah Desa']);
        DB::table('master_statushak_tanah')->updateOrInsert(['kdstatushaktanah' => 6], ['statushaktanah' => 'Tanah Negara']);

        // ============================
        // PENGGUNAAN TANAH
        // ============================
        DB::table('master_penggunaan_tanah')->updateOrInsert(['kdpenggunaantanah' => 1], ['penggunaantanah' => 'Kantor Desa']);
        DB::table('master_penggunaan_tanah')->updateOrInsert(['kdpenggunaantanah' => 2], ['penggunaantanah' => 'Lapangan']);
        DB::table('master_penggunaan_tanah')->updateOrInsert(['kdpenggunaantanah' => 3], ['penggunaantanah' => 'Sekolah']);
        DB::table('master_penggunaan_tanah')->updateOrInsert(['kdpenggunaantanah' => 4], ['penggunaantanah' => 'Sawah']);
        DB::table('master_penggunaan_tanah')->updateOrInsert(['kdpenggunaantanah' => 5], ['penggunaantanah' => 'Kebun']);
        DB::table('master_penggunaan_tanah')->updateOrInsert(['kdpenggunaantanah' => 6], ['penggunaantanah' => 'Tempat Pemakaman Umum']);

        // ============================
        // MUTASI TANAH
        // ============================
        DB::table('master_mutasi_tanah')->updateOrInsert(['kdmutasitanah' => 1], ['mutasitanah' => 'Tanah Awal']);
        DB::table('master_mutasi_tanah')->updateOrInsert(['kdmutasitanah' => 2], ['mutasitanah' => 'Dijual']);
        DB::table('master_mutasi_tanah')->updateOrInsert(['kdmutasitanah' => 3], ['mutasitanah' => 'Dihibahkan']);
        DB::table('master_mutasi_tanah')->updateOrInsert(['kdmutasitanah' => 4], ['mutasitanah' => 'Tukar Guling']);
        DB::table('master_mutasi_tanah')->updateOrInsert(['kdmutasitanah' => 5], ['mutasitanah' => 'Disewakan']);
        DB::table('master_mutasi_tanah')->updateOrInsert(['kdmutasitanah' => 6], ['mutasitanah' => 'Penghapusan']);

        // ============================
        // JENIS PEMILIK
        // ============================
        DB::table('master_jenispemilik')->updateOrInsert(['kdjenispemilik' => 1], ['jenispemilik' => 'Pemerintah Desa']);
        DB::table('master_jenispemilik')->updateOrInsert(['kdjenispemilik' => 2], ['jenispemilik' => 'Pribadi']);
        DB::table('master_jenispemilik')->updateOrInsert(['kdjenispemilik' => 3], ['jenispemilik' => 'Negara']);
        DB::table('master_jenispemilik')->updateOrInsert(['kdjenispemilik' => 4], ['jenispemilik' => 'Lembaga']);

        // ============================
        // JENIS AGENDA UMUM (BUKU AGENDA)
        // ============================
        $jenisAgendaUmum = [
            ['kdjenisagenda_umum' => '01', 'jenisagenda_umum' => 'Surat Masuk'],
            ['kdjenisagenda_umum' => '02', 'jenisagenda_umum' => 'Surat Keluar'],
            ['kdjenisagenda_umum' => '03', 'jenisagenda_umum' => 'Undangan'],
            ['kdjenisagenda_umum' => '04', 'jenisagenda_umum' => 'Pemberitahuan'],
            ['kdjenisagenda_umum' => '05', 'jenisagenda_umum' => 'Keputusan'],
        ];

        foreach ($jenisAgendaUmum as $row) {
            MasterJenisAgendaUmum::updateOrCreate(
                ['kdjenisagenda_umum' => $row['kdjenisagenda_umum']],
                $row
            );
        }

        // ============================
        // JENIS KEPUTUSAN UMUM
        // ============================
        $keputusanUmum = [
            ['kdjeniskeputusan_umum' => '01', 'jeniskeputusan_umum' => 'Surat Keputusan'],
            ['kdjeniskeputusan_umum' => '02', 'jeniskeputusan_umum' => 'Surat Peringatan'],
            ['kdjeniskeputusan_umum' => '03', 'jeniskeputusan_umum' => 'Surat Edaran'],
            ['kdjeniskeputusan_umum' => '04', 'jeniskeputusan_umum' => 'Instruksi'],
            ['kdjeniskeputusan_umum' => '05', 'jeniskeputusan_umum' => 'Pengumuman'],
        ];

        foreach ($keputusanUmum as $row) {
            MasterJenisKeputusanUmum::updateOrCreate(
                ['kdjeniskeputusan_umum' => $row['kdjeniskeputusan_umum']],
                $row
            );
        }
        // ============================
        // JENIS PERATURAN DESA
        // ============================
        $jenisPeraturan = [
            ['kdjenisperaturandesa' => '1', 'jenisperaturandesa' => 'Peraturan Desa'],
            ['kdjenisperaturandesa' => '2', 'jenisperaturandesa' => 'Peraturan Kepala Desa'],
            ['kdjenisperaturandesa' => '3', 'jenisperaturandesa' => 'Keputusan Kepala Desa'],
            ['kdjenisperaturandesa' => '4', 'jenisperaturandesa' => 'Peraturan Bersama'],
            ['kdjenisperaturandesa' => '5', 'jenisperaturandesa' => 'Instruksi Kepala Desa'],
        ];

        foreach ($jenisPeraturan as $row) {
            DB::table('master_jenisperaturandesa')->updateOrInsert(
                ['kdjenisperaturandesa' => $row['kdjenisperaturandesa']],
                ['jenisperaturandesa' => $row['jenisperaturandesa']]
            );
        }

        // ============================
        // JENIS TKD
        // ============================
        foreach (
            [
                ['01', 'Sawah'],
                ['02', 'Tegalan'],
                ['03', 'Pekarangan'],
                ['04', 'Kebun'],
                ['05', 'Tambak']
            ] as $j
        ) {
            MasterJenisTKD::updateOrCreate(['kdjenistkd' => $j[0]], ['jenistkd' => $j[1]]);
        }

        // ============================
        // PEROLEHAN TKD
        // ============================
        foreach (
            [
                ['01', 'Tanah Bengkok'],
                ['02', 'Tanah Titisara'],
                ['03', 'Tanah Desa'],
                ['04', 'Tanah Wakaf'],
                ['05', 'Tanah Hibah']
            ] as $p
        ) {
            MasterPerolehanTKD::updateOrCreate(['kdperolehantkd' => $p[0]], ['perolehantkd' => $p[1]]);
        }

        // ============================
        // PATOK
        // ============================
        foreach (
            [
                ['01', 'Ada'],
                ['02', 'Tidak Ada'],
                ['03', 'Rusak']
            ] as $p
        ) {
            MasterPatok::updateOrCreate(['kdpatok' => $p[0]], ['patok' => $p[1]]);
        }

        // ============================
        // PAPAN NAMA
        // ============================
        foreach (
            [
                ['01', 'Ada'],
                ['02', 'Tidak Ada'],
                ['03', 'Rusak']
            ] as $p
        ) {
            MasterPapanNama::updateOrCreate(['kdpapannama' => $p[0]], ['papannama' => $p[1]]);
        }

        // ============================
        // APARAT
        // ============================
        foreach (
            [
                [1, 'Polri'],
                [2, 'TNI'],
                [3, 'Satpol PP'],
                [4, 'Kejaksaan'],
                [5, 'Pengadilan']
            ] as $a
        ) {
            MasterAparat::updateOrCreate(['kdaparat' => $a[0]], ['aparat' => $a[1]]);
        }

        // ============================
        // ASAL BARANG
        // ============================
        DB::table('master_asalbarang')->updateOrInsert(['kdasalbarang' => 1], ['asalbarang' => 'APB Desa']);
        DB::table('master_asalbarang')->updateOrInsert(['kdasalbarang' => 2], ['asalbarang' => 'Bantuan Kabupaten']);
        DB::table('master_asalbarang')->updateOrInsert(['kdasalbarang' => 3], ['asalbarang' => 'Bantuan Provinsi']);
        DB::table('master_asalbarang')->updateOrInsert(['kdasalbarang' => 4], ['asalbarang' => 'Hibah']);

        // ============================
        // SATUAN BARANG
        // ============================
        DB::table('master_satuanbarang')->updateOrInsert(['kdsatuanbarang' => 1], ['satuanbarang' => 'Unit']);
        DB::table('master_satuanbarang')->updateOrInsert(['kdsatuanbarang' => 2], ['satuanbarang' => 'Buah']);
        DB::table('master_satuanbarang')->updateOrInsert(['kdsatuanbarang' => 3], ['satuanbarang' => 'Set']);
        DB::table('master_satuanbarang')->updateOrInsert(['kdsatuanbarang' => 4], ['satuanbarang' => 'Lembar']);

        // ============================
        // PENGGUNA BARANG
        // ============================
        DB::table('master_pengguna')->updateOrInsert(['kdpengguna' => 1], ['pengguna' => 'Kepala Desa']);
        DB::table('master_pengguna')->updateOrInsert(['kdpengguna' => 2], ['pengguna' => 'Sekretaris Desa']);
        DB::table('master_pengguna')->updateOrInsert(['kdpengguna' => 3], ['pengguna' => 'Kaur Umum']);
        DB::table('master_pengguna')->updateOrInsert(['kdpengguna' => 4], ['pengguna' => 'Bendahara']);
    }
}
