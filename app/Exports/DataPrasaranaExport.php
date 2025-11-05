<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataPrasaranaDasar;
use Illuminate\Support\Facades\DB;

class DataPrasaranaExport
{
    public static function export()
    {
        $filename = 'data_prasaranadasar_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // Tambah kolom Nama Kepala Keluarga di header
        $header = [
            'No KK',
            'Nama Kepala Keluarga',
            'Status Pemilik Bangunan',
            'Status Pemilik Lahan',
            'Jenis Fisik Bangunan',
            'Jenis Lantai Bangunan',
            'Kondisi Lantai Bangunan',
            'Jenis Dinding Bangunan',
            'Kondisi Dinding Bangunan',
            'Jenis Atap Bangunan',
            'Kondisi Atap Bangunan',
            'Sumber Air Minum',
            'Kondisi Sumber Air',
            'Cara Perolehan Air',
            'Sumber Penerangan Utama',
            'Sumber Daya Terpasang',
            'Bahan Bakar Memasak',
            'Fasilitas Tempat BAB',
            'Pembuangan Akhir Tinja',
            'Cara Pembuangan Sampah',
            'Manfaat Mata Air',
            'Luas Lantai (m²)',
            'Jumlah Kamar'
        ];
        $writer->addHeader($header);

        // Ambil semua data dengan join ke data_keluarga (nama kepala keluarga)
        $dataList = DataPrasaranaDasar::query()
            ->leftJoin('data_keluarga', 'data_prasaranadasar.no_kk', '=', 'data_keluarga.no_kk')
            ->leftJoin('master_statuspemilikbangunan', 'data_prasaranadasar.kdstatuspemilikbangunan', '=', 'master_statuspemilikbangunan.kdstatuspemilikbangunan')
            ->leftJoin('master_statuspemiliklahan', 'data_prasaranadasar.kdstatuspemiliklahan', '=', 'master_statuspemiliklahan.kdstatuspemiliklahan')
            ->leftJoin('master_jenisfisikbangunan', 'data_prasaranadasar.kdjenisfisikbangunan', '=', 'master_jenisfisikbangunan.kdjenisfisikbangunan')
            ->leftJoin('master_jenislantaibangunan', 'data_prasaranadasar.kdjenislantaibangunan', '=', 'master_jenislantaibangunan.kdjenislantaibangunan')
            ->leftJoin('master_kondisilantaibangunan', 'data_prasaranadasar.kdkondisilantaibangunan', '=', 'master_kondisilantaibangunan.kdkondisilantaibangunan')
            ->leftJoin('master_jenisdindingbangunan', 'data_prasaranadasar.kdjenisdindingbangunan', '=', 'master_jenisdindingbangunan.kdjenisdindingbangunan')
            ->leftJoin('master_kondisidindingbangunan', 'data_prasaranadasar.kdkondisidindingbangunan', '=', 'master_kondisidindingbangunan.kdkondisidindingbangunan')
            ->leftJoin('master_jenisatapbangunan', 'data_prasaranadasar.kdjenisatapbangunan', '=', 'master_jenisatapbangunan.kdjenisatapbangunan')
            ->leftJoin('master_kondisiatapbangunan', 'data_prasaranadasar.kdkondisiatapbangunan', '=', 'master_kondisiatapbangunan.kdkondisiatapbangunan')
            ->leftJoin('master_sumberairminum', 'data_prasaranadasar.kdsumberairminum', '=', 'master_sumberairminum.kdsumberairminum')
            ->leftJoin('master_kondisisumberair', 'data_prasaranadasar.kdkondisisumberair', '=', 'master_kondisisumberair.kdkondisisumberair')
            ->leftJoin('master_caraperolehanair', 'data_prasaranadasar.kdcaraperolehanair', '=', 'master_caraperolehanair.kdcaraperolehanair')
            ->leftJoin('master_sumberpeneranganutama', 'data_prasaranadasar.kdsumberpeneranganutama', '=', 'master_sumberpeneranganutama.kdsumberpeneranganutama')
            ->leftJoin('master_sumberdayaterpasang', 'data_prasaranadasar.kdsumberdayaterpasang', '=', 'master_sumberdayaterpasang.kdsumberdayaterpasang')
            ->leftJoin('master_bahanbakarmemasak', 'data_prasaranadasar.kdbahanbakarmemasak', '=', 'master_bahanbakarmemasak.kdbahanbakarmemasak')
            ->leftJoin('master_fasilitastempatbab', 'data_prasaranadasar.kdfasilitastempatbab', '=', 'master_fasilitastempatbab.kdfasilitastempatbab')
            ->leftJoin('master_pembuanganakhirtinja', 'data_prasaranadasar.kdpembuanganakhirtinja', '=', 'master_pembuanganakhirtinja.kdpembuanganakhirtinja')
            ->leftJoin('master_carapembuangansampah', 'data_prasaranadasar.kdcarapembuangansampah', '=', 'master_carapembuangansampah.kdcarapembuangansampah')
            ->leftJoin('master_manfaatmataair', 'data_prasaranadasar.kdmanfaatmataair', '=', 'master_manfaatmataair.kdmanfaatmataair')
            ->select(
                'data_prasaranadasar.no_kk',
                DB::raw('COALESCE(data_keluarga.keluarga_kepalakeluarga, "TIDAK DIISI") as nama_kepalakeluarga'),
                DB::raw('COALESCE(master_statuspemilikbangunan.statuspemilikbangunan, "TIDAK DIISI") as statuspemilikbangunan'),
                DB::raw('COALESCE(master_statuspemiliklahan.statuspemiliklahan, "TIDAK DIISI") as statuspemiliklahan'),
                DB::raw('COALESCE(master_jenisfisikbangunan.jenisfisikbangunan, "TIDAK DIISI") as jenisfisikbangunan'),
                DB::raw('COALESCE(master_jenislantaibangunan.jenislantaibangunan, "TIDAK DIISI") as jenislantaibangunan'),
                DB::raw('COALESCE(master_kondisilantaibangunan.kondisilantaibangunan, "TIDAK DIISI") as kondisilantaibangunan'),
                DB::raw('COALESCE(master_jenisdindingbangunan.jenisdindingbangunan, "TIDAK DIISI") as jenisdindingbangunan'),
                DB::raw('COALESCE(master_kondisidindingbangunan.kondisidindingbangunan, "TIDAK DIISI") as kondisidindingbangunan'),
                DB::raw('COALESCE(master_jenisatapbangunan.jenisatapbangunan, "TIDAK DIISI") as jenisatapbangunan'),
                DB::raw('COALESCE(master_kondisiatapbangunan.kondisiatapbangunan, "TIDAK DIISI") as kondisiatapbangunan'),
                DB::raw('COALESCE(master_sumberairminum.sumberairminum, "TIDAK DIISI") as sumberairminum'),
                DB::raw('COALESCE(master_kondisisumberair.kondisisumberair, "TIDAK DIISI") as kondisisumberair'),
                DB::raw('COALESCE(master_caraperolehanair.caraperolehanair, "TIDAK DIISI") as caraperolehanair'),
                DB::raw('COALESCE(master_sumberpeneranganutama.sumberpeneranganutama, "TIDAK DIISI") as sumberpeneranganutama'),
                DB::raw('COALESCE(master_sumberdayaterpasang.sumberdayaterpasang, "TIDAK DIISI") as sumberdayaterpasang'),
                DB::raw('COALESCE(master_bahanbakarmemasak.bahanbakarmemasak, "TIDAK DIISI") as bahanbakarmemasak'),
                DB::raw('COALESCE(master_fasilitastempatbab.fasilitastempatbab, "TIDAK DIISI") as fasilitastempatbab'),
                DB::raw('COALESCE(master_pembuanganakhirtinja.pembuanganakhirtinja, "TIDAK DIISI") as pembuanganakhirtinja'),
                DB::raw('COALESCE(master_carapembuangansampah.carapembuangansampah, "TIDAK DIISI") as carapembuangansampah'),
                DB::raw('COALESCE(master_manfaatmataair.manfaatmataair, "TIDAK DIISI") as manfaatmataair'),
                'data_prasaranadasar.prasdas_luaslantai',
                'data_prasaranadasar.prasdas_jumlahkamar'
            )
            ->get();

        // Tambah kolom nama kepala keluarga ke Excel
        foreach ($dataList as $item) {
            $writer->addRow([
                $item->no_kk,
                $item->nama_kepalakeluarga,
                $item->statuspemilikbangunan,
                $item->statuspemiliklahan,
                $item->jenisfisikbangunan,
                $item->jenislantaibangunan,
                $item->kondisilantaibangunan,
                $item->jenisdindingbangunan,
                $item->kondisidindingbangunan,
                $item->jenisatapbangunan,
                $item->kondisiatapbangunan,
                $item->sumberairminum,
                $item->kondisisumberair,
                $item->caraperolehanair,
                $item->sumberpeneranganutama,
                $item->sumberdayaterpasang,
                $item->bahanbakarmemasak,
                $item->fasilitastempatbab,
                $item->pembuanganakhirtinja,
                $item->carapembuangansampah,
                $item->manfaatmataair,
                $item->prasdas_luaslantai,
                $item->prasdas_jumlahkamar,
            ]);
        }

        $writer->close();

        return response()->download($path);
    }
}
