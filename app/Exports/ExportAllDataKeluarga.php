<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use OpenSpout\Writer\XLSX\Options; 
use Illuminate\Support\Facades\DB;

// Import semua model yang dibutuhkan
use App\Models\DataKeluarga;
use App\Models\DataPrasaranaDasar;
use App\Models\DataAsetKeluarga;
use App\Models\DataAsetLahan;
use App\Models\DataAsetTernak;
use App\Models\DataAsetPerikanan;
use App\Models\DataBangunKeluarga;
use App\Models\DataSejahteraKeluarga;
use App\Models\DataKonflikSosial;
use App\Models\DataSarprasKerja;
use App\Models\DataKualitasBayi;
use App\Models\DataKualitasIbuHamil;

// Master models
use App\Models\MasterDusun;
use App\Models\MasterMutasiMasuk;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use App\Models\MasterAsetKeluarga;
use App\Models\MasterJawab;
use App\Models\MasterAsetLahan;
use App\Models\MasterJawabLahan;
use App\Models\MasterAsetPerikanan;
use App\Models\MasterAsetTernak;
use App\Models\MasterKonflikSosial;
use App\Models\MasterJawabKonflik;
use App\Models\MasterSarprasKerja;
use App\Models\MasterJawabSarpras;
use App\Models\MasterKualitasBayi;
use App\Models\MasterJawabKualitasBayi;
use App\Models\MasterKualitasIbuHamil;
use App\Models\MasterJawabKualitasIbuHamil;

class ExportAllDataKeluarga
{
    public static function export()
    {
        $filename = 'export_all_data_keluarga_' . date('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/temp');
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $fullPath = $tempPath . '/' . $filename;

        // Buat writer Spatie
        $simpleWriter = SimpleExcelWriter::create($fullPath);

        // Akses writer OpenSpout untuk multiple sheet
        $writer = $simpleWriter->getWriter();

        // ===================================================================
        // 1. Sheet: Data Keluarga
        // ===================================================================
        $writer->getCurrentSheet()->setName('Data Keluarga');

        $simpleWriter->addHeader([
            'No KK',
            'Kepala Keluarga',
            'Tanggal Mutasi',
            'Jenis Mutasi',
            'Dusun',
            'RW',
            'RT',
            'Alamat Lengkap',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Desa',
        ]);

        foreach (DataKeluarga::cursor() as $keluarga) {
            $simpleWriter->addRow([
                'No KK' => $keluarga->no_kk, // String aman karena key label
                'Kepala Keluarga' => $keluarga->keluarga_kepalakeluarga,
                'Tanggal Mutasi' => $keluarga->keluarga_tanggalmutasi,
                'Jenis Mutasi' => optional(MasterMutasiMasuk::find($keluarga->kdmutasimasuk))->mutasimasuk ?? '-',
                'Dusun' => optional(MasterDusun::find($keluarga->kddusun))->dusun ?? '-',
                'RW' => $keluarga->keluarga_rw,
                'RT' => $keluarga->keluarga_rt,
                'Alamat Lengkap' => $keluarga->keluarga_alamatlengkap,
                'Provinsi' => optional(MasterProvinsi::find($keluarga->kdprovinsi))->provinsi ?? '-',
                'Kabupaten' => optional(MasterKabupaten::find($keluarga->kdkabupaten))->kabupaten ?? '-',
                'Kecamatan' => optional(MasterKecamatan::find($keluarga->kdkecamatan))->kecamatan ?? '-',
                'Desa' => optional(MasterDesa::find($keluarga->kddesa))->desa ?? '-',
            ]);
        }

        // ===================================================================
        // 2. Sheet: Prasarana Dasar
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Prasarana Dasar');

        $simpleWriter->addHeader([
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
        ]);

        $dataPrasarana = DataPrasaranaDasar::query()
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
            ->cursor();

        foreach ($dataPrasarana as $item) {
            $simpleWriter->addRow([
                'No KK' => $item->no_kk,
                'Nama Kepala Keluarga' => $item->nama_kepalakeluarga,
                'Status Pemilik Bangunan' => $item->statuspemilikbangunan,
                'Status Pemilik Lahan' => $item->statuspemiliklahan,
                'Jenis Fisik Bangunan' => $item->jenisfisikbangunan,
                'Jenis Lantai Bangunan' => $item->jenislantaibangunan,
                'Kondisi Lantai Bangunan' => $item->kondisilantaibangunan,
                'Jenis Dinding Bangunan' => $item->jenisdindingbangunan,
                'Kondisi Dinding Bangunan' => $item->kondisidindingbangunan,
                'Jenis Atap Bangunan' => $item->jenisatapbangunan,
                'Kondisi Atap Bangunan' => $item->kondisiatapbangunan,
                'Sumber Air Minum' => $item->sumberairminum,
                'Kondisi Sumber Air' => $item->kondisisumberair,
                'Cara Perolehan Air' => $item->caraperolehanair,
                'Sumber Penerangan Utama' => $item->sumberpeneranganutama,
                'Sumber Daya Terpasang' => $item->sumberdayaterpasang,
                'Bahan Bakar Memasak' => $item->bahanbakarmemasak,
                'Fasilitas Tempat BAB' => $item->fasilitastempatbab,
                'Pembuangan Akhir Tinja' => $item->pembuanganakhirtinja,
                'Cara Pembuangan Sampah' => $item->carapembuangansampah,
                'Manfaat Mata Air' => $item->manfaatmataair,
                'Luas Lantai (m²)' => $item->prasdas_luaslantai,
                'Jumlah Kamar' => $item->prasdas_jumlahkamar,
            ]);
        }

        // ===================================================================
        // 3. Sheet: Aset Keluarga
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Aset Keluarga');

        $asetList = MasterAsetKeluarga::orderBy('kdasetkeluarga')->pluck('asetkeluarga', 'kdasetkeluarga')->toArray();
        $jawabanList = MasterJawab::pluck('jawab', 'kdjawab')->toArray();

        $simpleWriter->addHeader(array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($asetList)));

        foreach (DataAsetKeluarga::cursor() as $keluarga) {
            $namaKepala = DataKeluarga::where('no_kk', $keluarga->no_kk)->value('keluarga_kepalakeluarga') ?? '-';

            $row = [
                'No KK' => $keluarga->no_kk,
                'Nama Kepala Keluarga' => $namaKepala,
            ];

            foreach ($asetList as $kode => $nama) {
                $kolom = "asetkeluarga_{$kode}";
                $row[$nama] = $jawabanList[$keluarga->$kolom ?? 0] ?? 'TIDAK DIISI';
            }

            $simpleWriter->addRow($row);
        }

        // ===================================================================
        // 4. Sheet: Aset Lahan
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Aset Lahan');

        $asetList = MasterAsetLahan::orderBy('kdasetlahan')->pluck('asetlahan', 'kdasetlahan')->toArray();
        $jawabanList = MasterJawabLahan::pluck('jawablahan', 'kdjawablahan')->toArray();

        $simpleWriter->addHeader(array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($asetList)));

        foreach (DataAsetLahan::cursor() as $lahan) {
            $namaKepala = DataKeluarga::where('no_kk', $lahan->no_kk)->value('keluarga_kepalakeluarga') ?? '-';

            $row = [
                'No KK' => $lahan->no_kk,
                'Nama Kepala Keluarga' => $namaKepala,
            ];

            foreach ($asetList as $kode => $nama) {
                $kolom = "asetlahan_{$kode}";
                $row[$nama] = $jawabanList[$lahan->$kolom ?? 0] ?? 'TIDAK DIISI';
            }

            $simpleWriter->addRow($row);
        }

        // ===================================================================
        // 5. Sheet: Aset Perikanan
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Aset Perikanan');

        $asetList = MasterAsetPerikanan::orderBy('kdasetperikanan')->pluck('asetperikanan', 'kdasetperikanan')->toArray();

        $simpleWriter->addHeader(array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($asetList)));

        foreach (DataAsetPerikanan::cursor() as $perikanan) {
            $namaKepala = DataKeluarga::where('no_kk', $perikanan->no_kk)->value('keluarga_kepalakeluarga') ?? '-';

            $row = [
                'No KK' => $perikanan->no_kk,
                'Nama Kepala Keluarga' => $namaKepala,
            ];

            foreach ($asetList as $kode => $nama) {
                $kolom = "asetperikanan_{$kode}";
                $row[$nama] = $perikanan->$kolom ?? 'TIDAK DIISI';
            }

            $simpleWriter->addRow($row);
        }

        // ===================================================================
        // 6. Sheet: Aset Ternak
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Aset Ternak');

        $asetList = MasterAsetTernak::orderBy('kdasetternak')->pluck('asetternak', 'kdasetternak')->toArray();

        $simpleWriter->addHeader(array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($asetList)));

        foreach (DataAsetTernak::cursor() as $ternak) {
            $namaKepala = DataKeluarga::where('no_kk', $ternak->no_kk)->value('keluarga_kepalakeluarga') ?? '-';

            $row = [
                'No KK' => $ternak->no_kk,
                'Nama Kepala Keluarga' => $namaKepala,
            ];

            foreach ($asetList as $kode => $nama) {
                $kolom = "asetternak_{$kode}";
                $row[$nama] = $ternak->$kolom ?? 'TIDAK DIISI';
            }

            $simpleWriter->addRow($row);
        }

        // ===================================================================
        // 7. Sheet: Sarpras Kerja
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Sarpras Kerja');

        $sarprasList = MasterSarprasKerja::orderBy('kdsarpraskerja')->pluck('sarpraskerja', 'kdsarpraskerja')->toArray();
        $jawabanList = MasterJawabSarpras::pluck('jawabsarpras', 'kdjawabsarpras')->toArray();

        $simpleWriter->addHeader(array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($sarprasList)));

        foreach (DataSarprasKerja::cursor() as $item) {
            $namaKepala = DataKeluarga::where('no_kk', $item->no_kk)->value('keluarga_kepalakeluarga') ?? '-';

            $row = [
                'No KK' => $item->no_kk,
                'Nama Kepala Keluarga' => $namaKepala,
            ];

            foreach ($sarprasList as $kode => $nama) {
                $kolom = "sarpraskerja_{$kode}";
                $idJawab = $item->$kolom ?? 1;
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $simpleWriter->addRow($row);
        }

        // ===================================================================
        // 8. Sheet: Bangun Keluarga
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Bangun Keluarga');

        $masterList = DB::table('master_pembangunankeluarga')
            ->where('kdtypejawab', 1)
            ->orderBy('kdpembangunankeluarga')
            ->pluck('pembangunankeluarga', 'kdpembangunankeluarga')
            ->toArray();

        $jawabanList = DB::table('master_jawabbangun')
            ->pluck('jawabbangun', 'kdjawabbangun')
            ->toArray();

        $simpleWriter->addHeader(array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($masterList)));

        foreach (DataBangunKeluarga::cursor() as $keluarga) {
            $namaKepala = DataKeluarga::where('no_kk', $keluarga->no_kk)->value('keluarga_kepalakeluarga') ?? '-';

            $row = [
                'No KK' => $keluarga->no_kk,
                'Nama Kepala Keluarga' => $namaKepala,
            ];

            foreach ($masterList as $kode => $nama) {
                $kolom = "bangunkeluarga_{$kode}";
                $idJawab = $keluarga->$kolom ?? 0;
                $row[$nama] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $simpleWriter->addRow($row);
        }

        // ===================================================================
        // 9. Sheet: Sejahtera Keluarga
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Sejahtera Keluarga');

        $simpleWriter->addHeader([
            'No KK', 'Nama Keluarga', 'Konsumsi Rokok per Hari', 'Frekuensi Makan per Hari',
            'Durasi Hiburan per Hari', 'Pengeluaran Harian (Rp)', 'Pendapatan Bulanan Suami (Rp)',
            'Pendapatan Bulanan Istri (Rp)', 'Total Pendapatan Keluarga (Rp)', 'Kepemilikan Kendaraan'
        ]);

        foreach (DataSejahteraKeluarga::with('keluarga')->cursor() as $item) {
            $simpleWriter->addRow([
                'No KK' => $item->no_kk,
                'Nama Keluarga' => $item->keluarga->keluarga_kepalakeluarga ?? 'Tidak Diketahui',
                'Konsumsi Rokok per Hari' => $item->sejahterakeluarga_61 ?? '-',
                'Frekuensi Makan per Hari' => $item->sejahterakeluarga_62 ?? '-',
                'Durasi Hiburan per Hari' => $item->sejahterakeluarga_63 ?? '-',
                'Pengeluaran Harian (Rp)' => $item->sejahterakeluarga_64 ?? '-',
                'Pendapatan Bulanan Suami (Rp)' => $item->sejahterakeluarga_65 ?? '-',
                'Pendapatan Bulanan Istri (Rp)' => $item->sejahterakeluarga_66 ?? '-',
                'Total Pendapatan Keluarga (Rp)' => $item->sejahterakeluarga_67 ?? '-',
                'Kepemilikan Kendaraan' => $item->sejahterakeluarga_68 ?? '-',
            ]);
        }

        // ===================================================================
        // 10. Sheet: Konflik Sosial
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Konflik Sosial');

        $konflikList = MasterKonflikSosial::orderBy('kdkonfliksosial')->pluck('konfliksosial', 'kdkonfliksosial')->toArray();
        $jawabanList = MasterJawabKonflik::pluck('jawabkonflik', 'kdjawabkonflik')->toArray();

        $simpleWriter->addHeader(array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($konflikList)));

        foreach (DataKonflikSosial::with('keluarga')->cursor() as $keluarga) {
            $simpleWriter->addRow(array_merge([
                'No KK' => $keluarga->no_kk,
                'Nama Kepala Keluarga' => $keluarga->keluarga->keluarga_kepalakeluarga ?? '-',
            ], collect($konflikList)->map(function ($nama, $kode) use ($keluarga, $jawabanList) {
                $kolom = "konfliksosial_{$kode}";
                return $jawabanList[$keluarga->$kolom ?? 0] ?? 'TIDAK DIISI';
            })->toArray()));
        }

        // ===================================================================
        // 11. Sheet: Kualitas Bayi
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Kualitas Bayi');

        $bayiList = MasterKualitasBayi::orderBy('kdkualitasbayi')->pluck('kualitasbayi', 'kdkualitasbayi')->toArray();
        $jawabanList = MasterJawabKualitasBayi::pluck('jawabkualitasbayi', 'kdjawabkualitasbayi')->toArray();

        $simpleWriter->addHeader(array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($bayiList)));

        $dataBayi = DataKualitasBayi::join('data_keluarga', 'data_keluarga.no_kk', '=', 'data_kualitasbayi.no_kk')
            ->select('data_kualitasbayi.*', 'data_keluarga.keluarga_kepalakeluarga')
            ->cursor();

        foreach ($dataBayi as $item) {
            $row = [
                'No KK' => $item->no_kk,
                'Nama Kepala Keluarga' => $item->keluarga_kepalakeluarga ?? '-',
            ];

            foreach ($bayiList as $kode => $nama) {
                $kolom = "kualitasbayi_{$kode}";
                $row[$nama] = $jawabanList[$item->$kolom ?? 0] ?? 'TIDAK DIISI';
            }

            $simpleWriter->addRow($row);
        }

        // ===================================================================
        // 12. Sheet: Kualitas Ibu Hamil
        // ===================================================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Kualitas Ibu Hamil');

        $ibuhamilList = MasterKualitasIbuHamil::orderBy('kdkualitasibuhamil')->pluck('kualitasibuhamil', 'kdkualitasibuhamil')->toArray();
        $jawabanList = MasterJawabKualitasIbuHamil::pluck('jawabkualitasibuhamil', 'kdjawabkualitasibuhamil')->toArray();

        $simpleWriter->addHeader(array_merge(['No KK', 'Nama Kepala Keluarga'], array_values($ibuhamilList)));

        $dataIbuHamil = DataKualitasIbuHamil::join('data_keluarga', 'data_keluarga.no_kk', '=', 'data_kualitasibuhamil.no_kk')
            ->select('data_kualitasibuhamil.*', 'data_keluarga.keluarga_kepalakeluarga')
            ->cursor();

        foreach ($dataIbuHamil as $item) {
            $row = [
                'No KK' => $item->no_kk,
                'Nama Kepala Keluarga' => $item->keluarga_kepalakeluarga ?? '-',
            ];

            foreach ($ibuhamilList as $kode => $nama) {
                $kolom = "kualitasibuhamil_{$kode}";
                $row[$nama] = $jawabanList[$item->$kolom ?? 0] ?? 'TIDAK DIISI';
            }

            $simpleWriter->addRow($row);
        }

        // Tutup writer
        $writer->close();

        // Return download + hapus file setelah download
        return response()->download($fullPath, $filename)->deleteFileAfterSend(true);
    }
}