<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Model data
use App\Models\DataPenduduk;
use App\Models\DataKelahiran;
use App\Models\DataSosialEkonomi;
use App\Models\DataUsahaArt;
use App\Models\DataProgramSerta;
use App\Models\DataLembagaDesa;
use App\Models\DataLembagaEkonomi;
use App\Models\DataLembagaMasyarakat;

// Master models (sesuaikan nama kolom jika berbeda)
use App\Models\MasterMutasimasuk;
use App\Models\MasterJeniskelamin;
use App\Models\MasterAgama;
use App\Models\MasterHubunganKeluarga;
use App\Models\MasterHubunganKepalaKeluarga;
use App\Models\MasterHubunganDalamKK;
use App\Models\MasterStatusKawin;
use App\Models\MasterAktaNikah;
use App\Models\MasterTercantumDalamKk;
use App\Models\MasterStatusTinggal;
use App\Models\MasterKartuIdentitas;
use App\Models\MasterPekerjaan;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;

use App\Models\MasterTempatPersalinan;
use App\Models\MasterJeniskelahiran;
use App\Models\MasterPertolonganPersalinan;

use App\Models\MasterPartisipasiSekolah;
use App\Models\MasterIjasahTerakhir;
use App\Models\MasterJenisDisabilitas;
use App\Models\MasterTingkatSulitDisabilitas;
use App\Models\MasterPenyakitKronis;
use App\Models\MasterLapanganUsaha;
use App\Models\MasterStatusKedudukanKerja;
use App\Models\MasterPendapatanPerbulan;
use App\Models\MasterImunisasi;

use App\Models\MasterOmsetUsaha;
use App\Models\MasterTempatUsaha;

use App\Models\MasterProgramSerta;
use App\Models\MasterJawabProgramSerta;
use App\Models\MasterLembagaDesa;
use App\Models\MasterJawabLemdes; // MasterJawabLembagaDesa
use App\Models\MasterJawabLemek;
use App\Models\MasterJawabLemmas;
use App\Models\MasterLembaga;


class ExportAllDataPenduduk
{
    public static function export()
    {
        $filename = 'export_all_data_penduduk_' . date('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/temp');
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $fullPath = $tempPath . '/' . $filename;

        $simpleWriter = SimpleExcelWriter::create($fullPath);
        $writer = $simpleWriter->getWriter();

        // =============================================
        // 1. Sheet: Data Penduduk (Versi Aman dengan Relasi Eloquent)
        // =============================================
        $writer->getCurrentSheet()->setName('Data Penduduk');
        $simpleWriter->addHeader([
            'NIK', 'No KK', 'Nama Kepala Keluarga', 'Jenis Mutasi', 'Tanggal Mutasi',
            'Kewarganegaraan', 'No Urut KK', 'Golongan Darah', 'No Akta Lahir',
            'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin',
            'Agama', 'Status Hubungan Keluarga', 'Status Hubungan Dalam KK',
            'Status Kawin', 'Kepemilikan Akta Nikah', 'Tercantum Dalam KK',
            'Status Tinggal', 'Kartu Identitas', 'Nama Ayah', 'Nama Ibu',
            'Pekerjaan', 'Nama Tempat Bekerja'
            
        ]);

        // Pastikan di model DataPenduduk sudah ada relasi seperti ini (contoh):
        // public function jenisKelamin() { return $this->belongsTo(MasterJeniskelamin::class, 'jeniskelamin_id'); }
        // public function agama() { return $this->belongsTo(MasterAgama::class, 'agama_id'); }
        // dst...

    $master_mutasimasuk = DB::table('master_mutasimasuk')
        ->pluck('mutasimasuk', 'kdmutasimasuk')
        ->toArray();


    $master_hubkel = DB::table('master_hubungankeluarga')
        ->pluck('hubungankeluarga', 'kdhubungankeluarga')
        ->toArray();

    $master_hubkk = DB::table('master_hubungankepalakeluarga')
        ->pluck('hubungankepalakeluarga', 'kdhubungankepalakeluarga')
        ->toArray();

    // =============================================
    // 3. QUERY DATA (EAGER LOAD RELASI)
    // =============================================
    $penduduk = DataPenduduk::with([
        'jenisKelamin',
        'agama',
        'statusKawin',
        'aktaNikah',
        'tercantumDalamKK',
        'statusTinggal',
        'kartuIdentitas',
        'pekerjaan',
    ])->cursor();


foreach ($penduduk as $p) {
    $simpleWriter->addRow([
        $p->nik ?? '-',
        $p->no_kk ?? '-',
        $p->penduduk_namalengkap ?? '-',

        $master_mutasimasuk[(int) $p->kdmutasimasuk] ?? '-',
        // TANGGAL MUTASI (disamakan)
        $p->penduduk_tanggalmutasi
            ? Carbon::parse($p->penduduk_tanggalmutasi)->format('Y-m-d')
            : '-',

        $p->penduduk_kewarganegaraan ?? '-',
        $p->penduduk_nourutkk ?? '-',
        $p->penduduk_goldarah ?? '-',
        $p->penduduk_noaktalahir ?? '-',

        $p->penduduk_namalengkap ?? '-',
        $p->penduduk_tempatlahir ?? '-',

        // TANGGAL LAHIR (disamakan)
        $p->penduduk_tanggallahir
            ? Carbon::parse($p->penduduk_tanggallahir)->format('Y-m-d')
            : '-',

        optional($p->jenisKelamin)->jeniskelamin ?? '-',
        optional($p->agama)->agama ?? '-',

        $master_hubkel[(int) $p->kdhubungankeluarga] ?? '-',
        $master_hubkk[(int) $p->kdhubungankepalakeluarga] ?? '-',

        optional($p->statusKawin)->statuskawin ?? '-',
        optional($p->aktaNikah)->aktanikah ?? '-',
        optional($p->tercantumDalamKK)->tercantumdalamkk ?? '-',
        optional($p->statusTinggal)->statustinggal ?? '-',
        optional($p->kartuIdentitas)->kartuidentitas ?? '-',
        $p->penduduk_namaayah ?? '-',
        $p->penduduk_namaibu ?? '-',
        optional($p->Pekerjaan)->pekerjaan ?? '-',
        $p->penduduk_namatempatbekerja ?? '-',
    ]);
}


        // =============================================
        // 2. Sheet: Kelahiran
        // =============================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Kelahiran');
        $simpleWriter->addHeader([
            'NIK', 'Nama Lengkap', 'Tempat Persalinan', 'Jenis Kelahiran',
            'Penolong Kelahiran', 'Jam Kelahiran', 'Kelahiran Ke',
            'Berat (gram)', 'Panjang (cm)', 'Identitas Ibu (NIK)', 'Identitas Ayah (NIK)',
        ]);

        foreach (DataKelahiran::with(['penduduk', 'tempatPersalinan', 'jenisKelahiran', 'pertolonganPersalinan'])->cursor() as $k) {
            $simpleWriter->addRow([
                $k->nik ?? '-',
                optional($k->penduduk)->penduduk_namalengkap ?? '-',
                optional($k->tempatPersalinan)->tempatpersalinan ?? '-',
                optional($k->jenisKelahiran)->jeniskelahiran ?? '-',
                optional($k->pertolonganPersalinan)->pertolonganpersalinan ?? '-',
                $k->kelahiran_jamkelahiran ?? '-',
                $k->kelahiran_kelahiranke ?? '-',
                $k->kelahiran_berat ?? '-',
                $k->kelahiran_panjang ?? '-',
                $k->kelahiran_nikibu ?? '-',
                $k->kelahiran_nikayah ?? '-',
            ]);
        }

        // =============================================
        // 3. Sheet: Sosial Ekonomi
        // =============================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Sosial Ekonomi');
        $simpleWriter->addHeader([
            'NIK', 'Nama Lengkap', 'Partisipasi Sekolah', 'Ijasah Terakhir',
            'Jenis Disabilitas', 'Tingkat Kesulitan Disabilitas', 'Penyakit Kronis',
            'Lapangan Usaha', 'Status Kedudukan Kerja', 'Pendapatan Per Bulan',
            'Cakupan Imunisasi',
        ]);

        foreach (DataSosialEkonomi::with([
            'penduduk',
            'partisipasiSekolah', 'ijasahTerakhir', 'jenisDisabilitas',
            'tingkatSulitDisabilitas', 'penyakitKronis', 'lapanganUsaha',
            'statusKedudukanKerja', 'pendapatanPerbulan', 'imunisasi'
        ])->cursor() as $se) {
            $simpleWriter->addRow([
                $se->nik ?? '-',
                optional($se->penduduk)->penduduk_namalengkap ?? '-',
                optional($se->partisipasiSekolah)->partisipasisekolah ?? '-',
                optional($se->ijasahTerakhir)->ijasahterakhir ?? '-',
                optional($se->jenisDisabilitas)->jenisdisabilitas ?? '-',
                optional($se->tingkatSulitDisabilitas)->tingkatsulitdisabilitas ?? '-',
                optional($se->penyakitKronis)->penyakitkronis ?? '-',
                optional($se->lapanganUsaha)->lapanganusaha ?? '-',
                optional($se->statusKedudukanKerja)->statuskedudukankerja ?? '-',
                optional($se->pendapatanPerbulan)->pendapatanperbulan ?? '-',
                optional($se->imunisasi)->imunisasi ?? '-',
            ]);
        }

        // =============================================
        // 4. Sheet: Usaha ART
        // =============================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Usaha ART');
        $simpleWriter->addHeader([
            'NIK', 'Nama Usaha', 'Lapangan Usaha', 'Tempat Usaha',
            'Omset Usaha', 'Jumlah Pekerja',
        ]);

        foreach (DataUsahaArt::with(['penduduk', 'lapanganUsaha', 'tempatUsaha', 'omsetUsaha'])->cursor() as $u) {
            $simpleWriter->addRow([
                $u->nik ?? '-',
                $u->usahaart_namausaha ?? '-',
                optional($u->lapanganUsaha)->lapanganusaha ?? '-',
                optional($u->tempatUsaha)->tempatusaha ?? '-',
                optional(value: $u->omsetUsaha)->omsetusaha ?? '-',
                $u->usahaart_jumlahpekerja ?? '-',
            ]);
        }

        // =============================================
        // 5. Sheet: Program Serta
        // =============================================
        $writer->addNewSheetAndMakeItCurrent();
        $writer->getCurrentSheet()->setName('Program Serta');

        $programList = MasterProgramSerta::orderBy('kdprogramserta')
            ->pluck('programserta', 'kdprogramserta')->toArray();

        $jawabList = MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta')->toArray();

        $header = array_merge(['NIK', 'Nama Lengkap'], array_values($programList));
        $simpleWriter->addHeader($header);

        foreach (DataProgramSerta::with('penduduk')->cursor() as $ps) {
            $row = [
                $ps->nik ?? '-',
                optional($ps->penduduk)->penduduk_namalengkap ?? '-',
            ];

            foreach ($programList as $kode => $nama) {
                $kolom = "programserta_{$kode}";
                $row[] = $jawabList[$ps->$kolom ?? 0] ?? 'TIDAK DIISI';
            }

            $simpleWriter->addRow($row);
        }

// =============================================
// 6. Sheet: Lembaga Desa
// =============================================
$writer->addNewSheetAndMakeItCurrent();
$writer->getCurrentSheet()->setName('Lembaga Desa');

// Ambil daftar lembaga desa (kdjenislembaga = 2), urut sesuai kdlembaga
$lembagaList = MasterLembaga::where('kdjenislembaga', 2)
    ->orderBy('kdlembaga')
    ->pluck('lembaga', 'kdlembaga')
    ->values() // reset index menjadi 0,1,2,...
    ->toArray();

// Daftar jawaban untuk lembaga desa
$jawabanList = MasterJawabLemdes::orderBy('kdjawablemdes')
    ->pluck('jawablemdes', 'kdjawablemdes')
    ->toArray();

// Header: NIK, Nama Lengkap, lalu nama-nama lembaga
$header = array_merge(['NIK', 'Nama Lengkap'], $lembagaList);
$simpleWriter->addHeader($header);

foreach (DataLembagaDesa::with('penduduk')->orderBy('nik')->cursor() as $ld) {
    $row = [
        $ld->nik ?? '-',
        optional($ld->penduduk)->penduduk_namalengkap ?? '-',
    ];

    // Urutan kolom: lemdes_1, lemdes_2, dst sesuai index + 1
    foreach ($lembagaList as $index => $namaLembaga) {
        $kolom = 'lemdes_' . ($index + 1);
        $idJawab = $ld->$kolom ?? 0;
        $row[] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
    }

    $simpleWriter->addRow($row);
}

// =============================================
// 7. Sheet: Lembaga Ekonomi
// =============================================
$writer->addNewSheetAndMakeItCurrent();
$writer->getCurrentSheet()->setName('Lembaga Ekonomi');

// Ambil daftar lembaga ekonomi (kdjenislembaga = 4)
$lembagaList = MasterLembaga::where('kdjenislembaga', 4)
    ->orderBy('kdlembaga')
    ->pluck('lembaga', 'kdlembaga')
    ->values()
    ->toArray();

// Daftar jawaban untuk lembaga ekonomi
$jawabanList = MasterJawabLemek::orderBy('kdjawablemek')
    ->pluck('jawablemek', 'kdjawablemek')
    ->toArray();

$header = array_merge(['NIK', 'Nama Lengkap'], $lembagaList);
$simpleWriter->addHeader($header);

foreach (DataLembagaEkonomi::with('penduduk')->orderBy('nik')->cursor() as $le) {
    $row = [
        $le->nik ?? '-',
        optional($le->penduduk)->penduduk_namalengkap ?? '-',
    ];

    foreach ($lembagaList as $index => $namaLembaga) {
        $kolom = 'lemek_' . ($index + 1);
        $idJawab = $le->$kolom ?? 0;
        $row[] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
    }

    $simpleWriter->addRow($row);
}

// =============================================
// 8. Sheet: Lembaga Masyarakat
// =============================================
$writer->addNewSheetAndMakeItCurrent();
$writer->getCurrentSheet()->setName('Lembaga Masyarakat');

// Ambil daftar lembaga masyarakat (kdjenislembaga = 3)
$lembagaList = MasterLembaga::where('kdjenislembaga', 3)
    ->orderBy('kdlembaga')
    ->pluck('lembaga', 'kdlembaga')
    ->values()
    ->toArray();

// Daftar jawaban untuk lembaga masyarakat
$jawabanList = MasterJawabLemmas::orderBy('kdjawablemmas')
    ->pluck('jawablemmas', 'kdjawablemmas')
    ->toArray();

$header = array_merge(['NIK', 'Nama Lengkap'], $lembagaList);
$simpleWriter->addHeader($header);

foreach (DataLembagaMasyarakat::with('penduduk')->orderBy('nik')->cursor() as $lm) {
    $row = [
        $lm->nik ?? '-',
        optional($lm->penduduk)->penduduk_namalengkap ?? '-',
    ];

    foreach ($lembagaList as $index => $namaLembaga) {
        $kolom = 'lemmas_' . ($index + 1);
        $idJawab = $lm->$kolom ?? 0;
        $row[] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
    }

    $simpleWriter->addRow($row);
}

        $writer->close();

        return response()->download($fullPath, $filename)->deleteFileAfterSend(true);
    }
}