<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MasterLembagaSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan sementara foreign key
        Schema::disableForeignKeyConstraints();

        // Truncate tabel agar fresh
        DB::table('master_lembaga')->truncate();
        DB::table('master_jenislembaga')->truncate();

        // Insert data master jenis lembaga
        DB::table('master_jenislembaga')->insert([
            ['kdjenislembaga' => 1, 'jenislembaga' => 'LEMBAGA PENDIDIKAN '],
            ['kdjenislembaga' => 2, 'jenislembaga' => 'LEMBAGA PEMERINTAHAN  DESA'],
            ['kdjenislembaga' => 3, 'jenislembaga' => 'LEMBAGA KEMASYARAKATAN'],
            ['kdjenislembaga' => 4, 'jenislembaga' => 'LEMBAGA EKONOMI'],
        ]);

        // Insert data master lembaga
        DB::table('master_lembaga')->insert([
        // Pemerintah Desa (kdjenislembaga = 2)
        ['kdlembaga' => 33, 'kdjenislembaga' => 2, 'lembaga' => 'KEPALA/LURAH'],
        ['kdlembaga' => 34, 'kdjenislembaga' => 2, 'lembaga' => 'SEKRETARIS/LURAH'],
        ['kdlembaga' => 35, 'kdjenislembaga' => 2, 'lembaga' => 'KEPALA URUSAN'],            
		['kdlembaga' => 36, 'kdjenislembaga' => 2, 'lembaga' => 'KEPALA DUSUN/LINGKUNGAN'],
        ['kdlembaga' => 37, 'kdjenislembaga' => 2, 'lembaga' => 'STAF'],
        ['kdlembaga' => 38, 'kdjenislembaga' => 2, 'lembaga' => 'KETUA BPD'],
        ['kdlembaga' => 39, 'kdjenislembaga' => 2, 'lembaga' => 'WAKIL KETUA BPD'],
        ['kdlembaga' => 40, 'kdjenislembaga' => 2, 'lembaga' => 'SEKRETARIS BPD'],
        ['kdlembaga' => 41, 'kdjenislembaga' => 2, 'lembaga' => 'ANGGOTA BPD'],

        // Lembaga Masyarakat (kdjenislembaga = 3)
		['kdlembaga' => 42, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS RT'],
		['kdlembaga' => 43, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS RW'],
		['kdlembaga' => 44, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS LKMD/K/LPM'],
		['kdlembaga' => 45, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS PKK'],
		['kdlembaga' => 46, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS LEMBAGA ADAT'],
		['kdlembaga' => 47, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS KARANG TARUNA'],
		['kdlembaga' => 48, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS HANSIP/LINMAS'],
		['kdlembaga' => 49, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS POSKAMLING'],
		['kdlembaga' => 50, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI PEREMPUAN'],
		['kdlembaga' => 51, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI BAPAK-BAPAK'],
		['kdlembaga' => 52, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI KEAGAMAAN'],
		['kdlembaga' => 53, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI PROFESI WARTAWAN'],
		['kdlembaga' => 54, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS POSYANDU'],
		['kdlembaga' => 55, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS POSYANTEKDES'],
		['kdlembaga' => 56, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI KELOMPOK TANI/NELAYAN'],
		['kdlembaga' => 57, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS LEMBAGA GOTONG ROYONG'],
		['kdlembaga' => 58, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI PROFESI GURU'],
		['kdlembaga' => 59, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI PROFESI DOKTER/TENAGA MEDIS'],
		['kdlembaga' => 60, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI PENSIUNAN'],
		['kdlembaga' => 61, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI PEMIRSA/PENDENGAR'],
		['kdlembaga' => 62, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS LEMBAGA PENCINTA ALAM'],
		['kdlembaga' => 63, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS ORGANISASI PENGEMBANGAN ILMU PENGETAHUAN'],
		['kdlembaga' => 64, 'kdjenislembaga' => 3, 'lembaga' => 'PEMILIK YAYASAN'],
		['kdlembaga' => 65, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS YAYASAN'],
		['kdlembaga' => 66, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS SATGAS KEBERSIHAN'],
		['kdlembaga' => 67, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS SATGAS KEBAKARAN'],
		['kdlembaga' => 68, 'kdjenislembaga' => 3, 'lembaga' => 'PENGURUS POSKO PENANGGULANGAN BENCANA'],


        // Lembaga Ekonomi (kdjenislembaga = 4)
        ['kdlembaga' => 90, 'kdjenislembaga' => 4, 'lembaga' => 'KOPERASI'],
	    ['kdlembaga' => 91, 'kdjenislembaga' => 4, 'lembaga' => 'UNIT USAHA SIMPAN PINJAM'],
	    ['kdlembaga' => 92, 'kdjenislembaga' => 4, 'lembaga' => 'INDUSTRI KERAJINAN TANGAN'],
	    ['kdlembaga' => 93, 'kdjenislembaga' => 4, 'lembaga' => 'INDUSTRI PAKAIAN'],
	    ['kdlembaga' => 94, 'kdjenislembaga' => 4, 'lembaga' => 'INDUSTRI USAHA MAKANAN'],
	    ['kdlembaga' => 95, 'kdjenislembaga' => 4, 'lembaga' => 'INDUSTRI ALAT RUMAH TANGGA'],
	    ['kdlembaga' => 96, 'kdjenislembaga' => 4, 'lembaga' => 'INDUSTRI USAHA BAHAN BANGUNAN'],
	    ['kdlembaga' => 97, 'kdjenislembaga' => 4, 'lembaga' => 'INDUSTRI ALAT PERTANIAN'],
	    ['kdlembaga' => 98, 'kdjenislembaga' => 4, 'lembaga' => 'RESTORAN'],
	    ['kdlembaga' => 99, 'kdjenislembaga' => 4, 'lembaga' => 'TOKO/ SWALAYAN'],
	    ['kdlembaga' => 100, 'kdjenislembaga' => 4, 'lembaga' => 'WARUNG KELONTONGAN/KIOS'],
	    ['kdlembaga' => 101, 'kdjenislembaga' => 4, 'lembaga' => 'ANGKUTAN DARAT'],
	    ['kdlembaga' => 102, 'kdjenislembaga' => 4, 'lembaga' => 'ANGKUTAN SUNGAI'],
	    ['kdlembaga' => 103, 'kdjenislembaga' => 4, 'lembaga' => 'ANGKUTAN LAUT'],
	    ['kdlembaga' => 104, 'kdjenislembaga' => 4, 'lembaga' => 'ANGKUTAN UDARA'],
	    ['kdlembaga' => 105, 'kdjenislembaga' => 4, 'lembaga' => 'JASA EKSPEDISI/PENGIRIMAN BARANG'],
	    ['kdlembaga' => 106, 'kdjenislembaga' => 4, 'lembaga' => 'TUKANG SUMUR'],
	    ['kdlembaga' => 107, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PASAR HARIAN'],
	    ['kdlembaga' => 108, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PASAR MINGGUAN'],
	    ['kdlembaga' => 109, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PASAR TERNAK'],
	    ['kdlembaga' => 110, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PASAR HASIL BUMI DAN TAMBANG'],
	    ['kdlembaga' => 111, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PERDAGANGAN ANTAR PULAU'],
	    ['kdlembaga' => 112, 'kdjenislembaga' => 4, 'lembaga' => 'PENGIJON'],
	    ['kdlembaga' => 113, 'kdjenislembaga' => 4, 'lembaga' => 'PEDAGANG PENGUMPUL/TENGKULAK'],
	    ['kdlembaga' => 114, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PETERNAKAN'],
	    ['kdlembaga' => 115, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PERIKANAN'],
	    ['kdlembaga' => 116, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PERKEBUNAN'],
	    ['kdlembaga' => 117, 'kdjenislembaga' => 4, 'lembaga' => 'KELOMPOK SIMPAN PINJAM'],
	    ['kdlembaga' => 118, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA MINUMAN'],
	    ['kdlembaga' => 119, 'kdjenislembaga' => 4, 'lembaga' => 'INDUSTRI FARMASI'],
	    ['kdlembaga' => 120, 'kdjenislembaga' => 4, 'lembaga' => 'INDUSTRI KAROSERI'],
	    ['kdlembaga' => 121, 'kdjenislembaga' => 4, 'lembaga' => 'PENITIPAN KENDARAAN BERMOTOR'],
	    ['kdlembaga' => 122, 'kdjenislembaga' => 4, 'lembaga' => 'INDUSTRI PERAKITAN ELEKTRONIK'],
	    ['kdlembaga' => 123, 'kdjenislembaga' => 4, 'lembaga' => 'PENGOLAHAN KAYU'],
	    ['kdlembaga' => 124, 'kdjenislembaga' => 4, 'lembaga' => 'BIOSKOP'],
	    ['kdlembaga' => 125, 'kdjenislembaga' => 4, 'lembaga' => 'FILM KELILING'],
	    ['kdlembaga' => 126, 'kdjenislembaga' => 4, 'lembaga' => 'SANDIWARA/DRAMA'],
	    ['kdlembaga' => 127, 'kdjenislembaga' => 4, 'lembaga' => 'GROUP LAWAK'],
	    ['kdlembaga' => 128, 'kdjenislembaga' => 4, 'lembaga' => 'JAIPONGAN'],
	    ['kdlembaga' => 129, 'kdjenislembaga' => 4, 'lembaga' => 'WAYANG ORANG/GOLEK'],
	    ['kdlembaga' => 130, 'kdjenislembaga' => 4, 'lembaga' => 'GROUP MUSIK/BAND'],
	    ['kdlembaga' => 131, 'kdjenislembaga' => 4, 'lembaga' => 'GROUP VOKAL/PADUAN SUARA'],
	    ['kdlembaga' => 132, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PERSEWAAN TENAGA LISTRIK'],
	    ['kdlembaga' => 133, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PENGECER GAS DAN BAHAN BAKAR MINYAK'],
	    ['kdlembaga' => 134, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA AIR MINUM DALAM KEMASAN'],
	    ['kdlembaga' => 135, 'kdjenislembaga' => 4, 'lembaga' => 'TUKANG KAYU'],
	    ['kdlembaga' => 136, 'kdjenislembaga' => 4, 'lembaga' => 'TUKANG BATU'],
	    ['kdlembaga' => 137, 'kdjenislembaga' => 4, 'lembaga' => 'TUKANG JAHIT/BORDIR'],
	    ['kdlembaga' => 138, 'kdjenislembaga' => 4, 'lembaga' => 'TUKANG CUKUR'],
	    ['kdlembaga' => 139, 'kdjenislembaga' => 4, 'lembaga' => 'TUKANG SERVICE ELEKTRONIK'],
	    ['kdlembaga' => 140, 'kdjenislembaga' => 4, 'lembaga' => 'TUKANG BESI'],
	    ['kdlembaga' => 141, 'kdjenislembaga' => 4, 'lembaga' => 'TUKANG PIJAT/URUT'],
	    ['kdlembaga' => 142, 'kdjenislembaga' => 4, 'lembaga' => 'TUKANG SUMUR'],
	    ['kdlembaga' => 143, 'kdjenislembaga' => 4, 'lembaga' => 'NOTARIS'],
	    ['kdlembaga' => 144, 'kdjenislembaga' => 4, 'lembaga' => 'PENGACARA/ADVOKAT'],
	    ['kdlembaga' => 145, 'kdjenislembaga' => 4, 'lembaga' => 'KONSULTAN MANAJEMEN'],
	    ['kdlembaga' => 146, 'kdjenislembaga' => 4, 'lembaga' => 'KONSULTAN TEKNIS'],
	    ['kdlembaga' => 147, 'kdjenislembaga' => 4, 'lembaga' => 'PEJABAT PEMBUAT AKTA TANAH'],
	    ['kdlembaga' => 148, 'kdjenislembaga' => 4, 'lembaga' => 'LOSMEN'],
	    ['kdlembaga' => 149, 'kdjenislembaga' => 4, 'lembaga' => 'WISMA'],
	    ['kdlembaga' => 150, 'kdjenislembaga' => 4, 'lembaga' => 'ASRAMA'],
	    ['kdlembaga' => 151, 'kdjenislembaga' => 4, 'lembaga' => 'PERSEWAAN KAMAR'],
	    ['kdlembaga' => 152, 'kdjenislembaga' => 4, 'lembaga' => 'KONTRAKAN RUMAH'],
	    ['kdlembaga' => 153, 'kdjenislembaga' => 4, 'lembaga' => 'MESS'],
	    ['kdlembaga' => 154, 'kdjenislembaga' => 4, 'lembaga' => 'HOTEL'],
	    ['kdlembaga' => 155, 'kdjenislembaga' => 4, 'lembaga' => 'HOME STAY'],
	    ['kdlembaga' => 156, 'kdjenislembaga' => 4, 'lembaga' => 'VILLA'],
	    ['kdlembaga' => 157, 'kdjenislembaga' => 4, 'lembaga' => 'TOWN HOUSE'],
	    ['kdlembaga' => 158, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA ASURANSI'],
	    ['kdlembaga' => 159, 'kdjenislembaga' => 4, 'lembaga' => 'LEMBAGA KEUANGAN BUKAN BANK'],
	    ['kdlembaga' => 160, 'kdjenislembaga' => 4, 'lembaga' => 'LEMBAGA PERKREDITAN RAKYAT'],
	    ['kdlembaga' => 161, 'kdjenislembaga' => 4, 'lembaga' => 'PEGADAIAN'],
	    ['kdlembaga' => 162, 'kdjenislembaga' => 4, 'lembaga' => 'BANK PERKREDITAN RAKYAT'],
	    ['kdlembaga' => 163, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PENYEWAAN ALAT PESTA'],
	    ['kdlembaga' => 164, 'kdjenislembaga' => 4, 'lembaga' => 'USAHA PENGOLAHAN DAN PENJUALAN HASIL HUTAN'],
        ]);

        // Aktifkan kembali foreign key
        Schema::enableForeignKeyConstraints();
    }
}