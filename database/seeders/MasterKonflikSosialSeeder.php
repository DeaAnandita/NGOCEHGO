<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterKonflikSosialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('master_konfliksosial')->truncate();

        DB::table('master_konfliksosial')->insert([
            ['kdkonfliksosial' => 1, 'konfliksosial' => 'KORBAN LUKA DALAM KELUARGA AKIBAT KONFLIK SARA'],
            ['kdkonfliksosial' => 2, 'konfliksosial' => 'KORBAN MENINGGAL DALAM KELUARGA AKIBAT KONFLIK SARA '],
            ['kdkonfliksosial' => 3, 'konfliksosial' => 'JANDA/DUDA DALAM KELUARGA AKIBAT KONFLIK SARA '],
            ['kdkonfliksosial' => 4, 'konfliksosial' => 'ANAK YATIM/PIATU DALAM KELUARGA AKIBAT KONFLIK SARA'],
            ['kdkonfliksosial' => 5, 'konfliksosial' => 'KORBAN JIWA AKIBAT PERKELAHIAN DALAM KELUARGA'],
            ['kdkonfliksosial' => 6, 'konfliksosial' => 'KORBAN LUKA PARAH AKIBAT PERKELAHIAN DALAM KELUARGA'],
            ['kdkonfliksosial' => 7, 'konfliksosial' => 'KORBAN PENCURIAN, PERAMPOKAN DALAM KELUARGA'],
            ['kdkonfliksosial' => 8, 'konfliksosial' => 'KORBAN PENCURIAN, PERAMPOKAN DALAM KELUARGA'],
            ['kdkonfliksosial' => 9, 'konfliksosial' => 'KORBAN PENJARAHAN YANG PELAKUNYA BUKAN ANGGOTA KELUARGA'],
            ['kdkonfliksosial' => 10, 'konfliksosial' => 'ANGGOTA KELUARGA YANG MEMILIKI KEBIASAAN BERJUDI'],
	        ['kdkonfliksosial' => 11, 'konfliksosial' => 'ANGGOTA KELUARGA MENGKONSUMSI MIRAS YANG DILARANG'],
            ['kdkonfliksosial' => 12, 'konfliksosial' => 'ANGGOTA KELUARGA YANG MENGKONSUMSI NARKOBA'],
            ['kdkonfliksosial' => 13, 'konfliksosial' => 'KORBAN PEMBUNUHAN DALAM KELUARGA YANG PELAKUNYA ANGGOTA KELUARGA'],
            ['kdkonfliksosial' => 14, 'konfliksosial' => 'KORBAN PEMBUNUHAN DALAM KELUARGA YANG PELAKUNYA BUKAN ANGGOTA KELUARGA'],
            ['kdkonfliksosial' => 15, 'konfliksosial' => 'KORBAN PENCULIKAN YANG PELAKUNYA ANGGOTA KELUARGA'],
            ['kdkonfliksosial' => 16, 'konfliksosial' => 'KORBAN PENCULIKAN YANG PELAKUNYA BUKAN ANGGOTA KELUARGA'],
            ['kdkonfliksosial' => 17, 'konfliksosial' => 'KORBAN PERKOSAAN/PELECEHAN SEKSUAL YANG PELAKUNYA ANGGOTA KELUARGA'],
            ['kdkonfliksosial' => 18, 'konfliksosial' => 'KORBAN PERKOSAAN/PELECEHAN SEKSUAL YANG PELAKUNYA BUKAN ANGGOTA KELUARGA'],
            ['kdkonfliksosial' => 19, 'konfliksosial' => 'KORBAN KEHAMILAN DI LUAR NIKAH YANG SAH MENURUT HUKUM ADAT'],
            ['kdkonfliksosial' => 20, 'konfliksosial' => 'KORBAN KEHAMILAN YANG TIDAK DINIKAHI PELAKUNYA'],
	        ['kdkonfliksosial' => 21, 'konfliksosial' => 'KORBAN KEHAMILAN YANG TIDAK/BELUM DISAHKAN SECARA HUKUM AGAMA DAN HUKUM NEGARA'],
            ['kdkonfliksosial' => 22, 'konfliksosial' => 'ADANYA PERTENGKARAN DALAM KELUARGA ANTARA ANAK DAN ORANG TUA'],
            ['kdkonfliksosial' => 23, 'konfliksosial' => 'ADANYA PERTENGKARAN DALAM KELUARGA ANTARA ANAK DAN ANAK'],
            ['kdkonfliksosial' => 24, 'konfliksosial' => 'ADANYA PERTENGKARAN DALAM KELUARGA ANTARA AYAH DAN IBU/ORANG TUA'],
            ['kdkonfliksosial' => 25, 'konfliksosial' => 'ADANYA PERTENGKARAN DALAM KELUARGA ANTARA ANAK DAN PEMBANTU'],
            ['kdkonfliksosial' => 26, 'konfliksosial' => 'ADANYA PERTENGKARAN DALAM KELUARGA ANTARA ANAK DAN ANGGOTA KELUARGA LAIN'],
            ['kdkonfliksosial' => 27, 'konfliksosial' => 'ADANYA PEMUKULAN/TINDAKAN FISIK ANTARA ANAK DENGAN ORANG TUA'],
            ['kdkonfliksosial' => 28, 'konfliksosial' => 'ADANYA PEMUKULAN/TINDAKAN FISIK ANTARA ORANG TUA DENGAN ANAK'],
            ['kdkonfliksosial' => 29, 'konfliksosial' => 'ADANYA PEMUKULAN/TINDAKAN FISIK ANTARA ANAK DENGAN ANGGOTA KELUARGA LAIN'],
            ['kdkonfliksosial' => 30, 'konfliksosial' => 'ADANYA PEMUKULAN/TINDAKAN FISIK ANTARA ORANG TUA DENGAN ORANG TUA'],
	        ['kdkonfliksosial' => 31, 'konfliksosial' => 'ADANYA PEMUKULAN/TINDAKAN FISIK ANTARA ANAK DENGAN PEMBANTU'],
            ['kdkonfliksosial' => 32, 'konfliksosial' => 'ADANYA PEMUKULAN/TINDAKAN FISIK ANTARA ORANG TUA DENGAN PEMBANTU'],
        ]);

    }
}
