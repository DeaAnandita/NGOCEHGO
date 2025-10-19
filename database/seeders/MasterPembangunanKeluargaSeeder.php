<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MasterPembangunanKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan sementara foreign key supaya truncate aman
        Schema::disableForeignKeyConstraints();
        DB::table('master_pembangunankeluarga')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('master_typejawab')->insert([
            ['kdtypejawab' => 1, 'typejawab' => 'PILIHAN'],
            ['kdtypejawab' => 2, 'typejawab' => 'URAIAN'],
        ]);

        // Insert data master pembangunan keluarga
        DB::table('master_pembangunankeluarga')->insert([
            ['kdpembangunankeluarga' => 1, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga membeli satu stel pakaian baru untuk seluruh anggota keluarga'],
            ['kdpembangunankeluarga' => 2, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Seluruh anggota keluarga makan minimal 2 kali sehari'],
            ['kdpembangunankeluarga' => 3, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Seluruh anggota keluarga bila sakit berobat ke fasilitas kesehatan'],
            ['kdpembangunankeluarga' => 4, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Seluruh anggota keluarga memiliki pakaian yang bersih dan layak pakai'],
            ['kdpembangunankeluarga' => 5, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Seluruh anggota keluarga makan daging / ikan / telur / tempe / tahu'],
            ['kdpembangunankeluarga' => 6, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Seluruh anggota keluarga menjalankan ibadah sesuai agama dan keyakinan'],
            ['kdpembangunankeluarga' => 7, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Pasangan usia subur dengan dua anak atau lebih memiliki KB aktif'],
            ['kdpembangunankeluarga' => 8, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga memiliki tabungan dalam bentuk uang / emas / deposito'],
            ['kdpembangunankeluarga' => 9, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga memiliki kebiasaan berkomunikasi dengan sesama anggota keluarga'],
            ['kdpembangunankeluarga' => 10, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga ikut dalam kegiatan sosial di lingkungan RT'],
            ['kdpembangunankeluarga' => 11, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga memiliki akses informasi dari surat kabar, majalah, radio, TV atau internet'],
            ['kdpembangunankeluarga' => 12, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga memiliki anggota yang menjadi pengurus kelompok/organisasi di masyarakat'],
            ['kdpembangunankeluarga' => 13, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga memiliki balita ikut kegiatan posyandu'],
            ['kdpembangunankeluarga' => 14, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga memiliki balita ikut kegiatan BKB'],
            ['kdpembangunankeluarga' => 15, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga memiliki remaja ikut kegiatan BKR'],
            ['kdpembangunankeluarga' => 16, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga memiliki remaja ikut kegiatan PIK-R/M'],
            ['kdpembangunankeluarga' => 17, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga memiliki anggota lansia ikut kegiatan BKL'],
            ['kdpembangunankeluarga' => 18, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga mengikuti kegiatan UPPKS'],
            ['kdpembangunankeluarga' => 19, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga yang mengemis'],
            ['kdpembangunankeluarga' => 20, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga yang bermalam/tidur di jalan'],
            ['kdpembangunankeluarga' => 21, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga yang termasuk manusia lanjut usia tidak produktif'],
            ['kdpembangunankeluarga' => 22, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anak anggota keluarga yang mengemis'],
            ['kdpembangunankeluarga' => 23, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anak dan anggota keluarga yang menjadi pengamen'],
            ['kdpembangunankeluarga' => 24, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga yang gila/stres'],
            ['kdpembangunankeluarga' => 25, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga yang cacat fisik'],
            ['kdpembangunankeluarga' => 26, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga yang cacat mental'],
            ['kdpembangunankeluarga' => 27, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga yang kelainan kulit'],
            ['kdpembangunankeluarga' => 28, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga yang menjadi pengamen'],
            ['kdpembangunankeluarga' => 29, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Anggota keluarga yatim/piatu'],
            ['kdpembangunankeluarga' => 30, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga janda'],
            ['kdpembangunankeluarga' => 31, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Keluarga duda'],
            ['kdpembangunankeluarga' => 32, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di bantaran sungai'],
            ['kdpembangunankeluarga' => 33, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di jalur hijau'],
            ['kdpembangunankeluarga' => 34, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di kawasan jalur rel kereta api'],
            ['kdpembangunankeluarga' => 35, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di kawasan jalur sutet'],
            ['kdpembangunankeluarga' => 36, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di kawasan kumuh dan padat pemukiman'],
            ['kdpembangunankeluarga' => 37, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga yang menganggur'],
            ['kdpembangunankeluarga' => 38, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anak yang membantu orang tua mendapatkan penghasilan'],
            ['kdpembangunankeluarga' => 39, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Kepala keluarga perempuan'],
            ['kdpembangunankeluarga' => 40, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Ada anggota keluarga eks narapidana'],
            ['kdpembangunankeluarga' => 41, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di desa/kelurahan rawan banjir'],
            ['kdpembangunankeluarga' => 42, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di daerah rawan bencana tsunami'],
            ['kdpembangunankeluarga' => 43, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di desa/kelurahan rawan gunung meletus'],
            ['kdpembangunankeluarga' => 44, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di jalur rawan gempa bumi'],
            ['kdpembangunankeluarga' => 45, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di kawasan rawan tanah longsor'],
            ['kdpembangunankeluarga' => 46, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di kawasan rawan kebakaran'],
            ['kdpembangunankeluarga' => 47, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di desa/kelurahan rawan kelaparan'],
            ['kdpembangunankeluarga' => 48, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di desa/kelurahan rawan air bersih'],
            ['kdpembangunankeluarga' => 49, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di desa/kelurahan rawan kekeringan'],
            ['kdpembangunankeluarga' => 50, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di desa/kelurahan rawan gagal tanam/panen'],
            ['kdpembangunankeluarga' => 51, 'kdtypejawab' => 1, 'pembangunankeluarga' => 'Tinggal di daerah kawasan kering, tandus & kritis'],
            ['kdpembangunankeluarga' => 52, 'kdtypejawab' => 2, 'pembangunankeluarga' => 'Rata-rata uang saku anak untuk sekolah (perhari)'],
            ['kdpembangunankeluarga' => 53, 'kdtypejawab' => 2, 'pembangunankeluarga' => 'Keluarga memiliki kebiasaan merokok (Jika ya, ... bungkus perhari)'],
            ['kdpembangunankeluarga' => 54, 'kdtypejawab' => 2, 'pembangunankeluarga' => 'Kepala keluarga memiliki kebiasaan minum kopi di kedai ( ... kali)'],
            ['kdpembangunankeluarga' => 55, 'kdtypejawab' => 2, 'pembangunankeluarga' => 'Kepala keluarga memiliki kebiasaan minum kopi di kedai (... jam perhari)'],
            ['kdpembangunankeluarga' => 56, 'kdtypejawab' => 2, 'pembangunankeluarga' => 'Rata-rata pulsa yang digunakan keluarga (seminggu)'],
            ['kdpembangunankeluarga' => 57, 'kdtypejawab' => 2, 'pembangunankeluarga' => 'Rata-rata pendapatan / penghasilan keluarga sebulan'],
            ['kdpembangunankeluarga' => 58, 'kdtypejawab' => 2, 'pembangunankeluarga' => 'Rata-rata pengeluaran keluarga sebulan'],
            ['kdpembangunankeluarga' => 59, 'kdtypejawab' => 2, 'pembangunankeluarga' => 'Rata-rata uang belanja keluarga sebulan'],
        ]);
    }
}
