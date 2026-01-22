<?php

use App\Http\Controllers\Pelayanan\SuratController;
use App\Http\Controllers\AdminUmum\BukuPeraturanController;
use App\Http\Controllers\AdminUmum\BukuKeputusanController;
use App\Http\Controllers\AdminUmum\BukuAgendaLembagaController;
use App\Http\Controllers\AdminUmum\BukuAparatController;
use App\Http\Controllers\AdminUmum\BukuTanahDesaController;
use App\Http\Controllers\AdminUmum\BukuTanahKasDesaController;
use App\Http\Controllers\AdminUmum\BukuEkspedisiController;
use App\Http\Controllers\AdminUmum\BukuInventarisController;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Kelembagaan\PengurusKelembagaanController;
use App\Http\Controllers\Kelembagaan\KeputusanController;
use App\Http\Controllers\Kelembagaan\KegiatanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    MasterController,
    KependudukanController,
    ExportAllController,
    KeluargaController,
    PendudukController,
    PrasaranaDasarController,
    AsetKeluargaController,
    AsetLahanController,
    AsetPerikananController,
    AsetTernakController,
    SarprasKerjaController,
    BangunKeluargaController,
    SejahteraKeluargaController,
    KonflikSosialController,
    KualitasIbuHamilController,
    KualitasBayiController,
    KelahiranController,
    SosialEkonomiController,
    UsahaArtController,
    ProgramSertaController,
    LembagaDesaController,
    LembagaMasyarakatController,
    LembagaEkonomiController,
};
use App\Http\Controllers\Voice\{
    VoiceKeluargaController,
    VoiceKelembagaanController,
    WilayahController,
    VoicePrasaranaController,
    MenuVoiceController,
    VoiceController,
    VoicePelayananController,
    VoicePembangunanController,
    VoicePendudukController,
    VoiceUmumController
};
use App\Exports\{
    ExportAllDataKeluarga,
    DataKualitasIbuHamilExport,
    DataKeluargaExport,
    DataLembagaEkonomiExport,
    DataLembagamasyarakatExport,
    DataAsetKeluargaExport,
    DataPrasaranaExport,
    DataSejahteraKeluargaExport,
    DataKonflikSosialExport,
    DataPendudukExport,
    DataKelahiranExport,
    DataSosialEkonomiExport,
    DataUsahaArtExport,
    DataProgramSertaExport,
    DataLembagaDesaExport,
    DataKualitasBayiExport,
    DataAsetLahanExport,
    DataAsetTernakExport,
    DataAsetPerikananExport,
    DataSarprasKerjaExport,
    DataBangunKeluargaExport
};
use App\Http\Controllers\AdminPembangunan\BukuBantuanController;
use App\Http\Controllers\AdminPembangunan\BukuKaderController;
use App\Http\Controllers\AdminPembangunan\BukuProyekController;
use App\Http\Controllers\Kelembagaan\AgendaKelembagaanController;
use App\Http\Controllers\Kelembagaan\AnggaranKelembagaanController;
use App\Http\Controllers\Kelembagaan\KegiatanAnggaranController;
use App\Http\Controllers\Kelembagaan\LpjKegiatanController;
use App\Http\Controllers\Kelembagaan\PencairanDanaController;
use App\Http\Controllers\Kelembagaan\RealisasiPengeluaranController;

Route::get('/demo-login', function () {
    Auth::loginUsingId(1); // ID user demo
    return redirect('/dashboard');
});

Route::get('/export-all-keluarga', function () {
    return \App\Exports\ExportAllDataKeluarga::export();
})->name('export.all.keluarga');

// AJAX Wilayah (untuk keluarga)
Route::get('/get-kabupaten/{kdprovinsi}', [VoiceKeluargaController::class, 'getKabupaten']);
Route::get('/get-kecamatan/{kdkabupaten}', [VoiceKeluargaController::class, 'getKecamatan']);
Route::get('/get-desa/{kdkecamatan}', [VoiceKeluargaController::class, 'getDesa']);

// AJAX Wilayah (untuk penduduk)
Route::get('/voice/get-kabupaten/{kdprovinsi}', [VoicePendudukController::class, 'getKabupaten']);
Route::get('/voice/get-kecamatan/{kdkabupaten}', [VoicePendudukController::class, 'getKecamatan']);
Route::get('/voice/get-desa/{kdkecamatan}', [VoicePendudukController::class, 'getDesa']);

// ===============================
// VOICE ROUTES
// ===============================
Route::prefix('admin/voice')->name('voice.')->middleware('auth')->group(function () {

    // Menu utama
    Route::get('/', [MenuVoiceController::class, 'index'])->name('menu');

    // -----------------------------
    // Voice Keluarga
    // -----------------------------
    Route::get('/keluarga', [VoiceKeluargaController::class, 'index'])->name('keluarga.index');
    Route::post('/validate', [VoiceKeluargaController::class, 'validateVoice'])->name('validate');
    Route::post('/keluarga/store-all', [VoiceKeluargaController::class, 'storeAll'])
        ->name('keluarga.store-all'); // ← ROUTE KHUSUS KELUARGA

    // -----------------------------
    // Voice Penduduk
    // -----------------------------
    Route::get('/penduduk', [VoicePendudukController::class, 'index'])->name('penduduk.index');
    Route::post('/validate', [VoicePendudukController::class, 'validateVoice'])->name('validate');
    Route::post('/penduduk/store-all', [VoicePendudukController::class, 'storeAll'])
        ->name('penduduk.store-all'); // ← ROUTE KHUSUS PENDUDUK

    // AJAX Wilayah (bisa dipakai bersama)
    Route::get('/get-kabupaten/{kdprovinsi}', [VoiceKeluargaController::class, 'getKabupaten']);
    Route::get('/get-kecamatan/{kdkabupaten}', [VoiceKeluargaController::class, 'getKecamatan']);
    Route::get('/get-desa/{kdkecamatan}', [VoiceKeluargaController::class, 'getDesa']);

    Route::get('/penduduk/get-kabupaten/{kdprovinsi}', [VoicePendudukController::class, 'getKabupaten']);
    Route::get('/penduduk/get-kecamatan/{kdkabupaten}', [VoicePendudukController::class, 'getKecamatan']);
    Route::get('/penduduk/get-desa/{kdkecamatan}', [VoicePendudukController::class, 'getDesa']);
});

// ===============================
// HALAMAN AWAL
// ===============================
Route::view('/', 'welcome');
Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

// ===============================
// PROFILE
// ===============================
Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

// ===============================
// EXPORT EXCEL
// ===============================
Route::prefix('export')->group(function () {
    Route::get('aset-keluarga', fn() => DataAsetKeluargaExport::export())->name('export.asetkeluarga');
    Route::get('aset-lahan', fn() => DataAsetLahanExport::export())->name('export.asetlahan');
    Route::get('data-keluarga', fn() => DataKeluargaExport::export())->name('export.datakeluarga');
    Route::get('data-prasarana', fn() => DataPrasaranaExport::export())->name('export.dataprasarana');
    Route::get('sejahtera-keluarga', fn() => DataSejahteraKeluargaExport::export())->name('export.sejahterakeluarga');
    Route::get('konflik-sosial', fn() => DataKonflikSosialExport::export())->name('export.konfliksosial');
    Route::get('penduduk', fn() => DataPendudukExport::export())->name('export.penduduk');
    Route::get('kelahiran', fn() => DataKelahiranExport::export())->name('export.kelahiran');
    Route::get('sosial-ekonomi', fn() => DataSosialEkonomiExport::export())->name('export.sosialekonomi');
    Route::get('usaha-art', fn() => DataUsahaArtExport::export())->name('export.usahaart');
    Route::get('program-serta', fn() => DataProgramSertaExport::export())->name('export.programserta');
    Route::get('lembaga-desa', fn() => DataLembagaDesaExport::export())->name('export.lembagadesa');
    Route::get('lembaga-ekonomi', fn() => DataLembagaEkonomiExport::export())->name('export.lembagaekonomi');
    Route::get('lembaga-masyarakat', fn() => DataLembagaMasyarakatExport::export())->name('export.lembagamasyarakat');
    Route::get('kualitas-ibu-hamil', [DataKualitasIbuHamilExport::class, 'export'])->name('export.kualitasibuhamil');
    Route::get('kualitas-bayi', [DataKualitasBayiExport::class, 'export'])->name('export.kualitasbayi');
    Route::get('aset-ternak', fn() => DataAsetTernakExport::export())->name('export.asetternak');
    Route::get('aset-perikanan', fn() => DataAsetPerikananExport::export())->name('export.asetperikanan');
    Route::get('sarpras-kerja', fn() => DataSarprasKerjaExport::export())->name('export.sarpraskerja');
    Route::get('bangun-keluarga', fn() => DataBangunKeluargaExport::export())->name('export.bangunkeluarga');
});

// ===============================
// MASTER DATA
// ===============================
Route::prefix('master')->name('master.')->controller(MasterController::class)->group(function () {
    Route::view('/list', 'master.index')->name('list');
    Route::get('/{master}', 'index')->name('index');
    Route::get('/{master}/create', 'create')->name('create');
    Route::post('/{master}/store', 'store')->name('store');
    Route::get('/{master}/edit/{id}', 'edit')->name('edit');
    Route::put('/{master}/update/{id}', 'update')->name('update');
    Route::delete('/{master}/destroy/{id}', 'destroy')->name('destroy');
});

// ===============================
// ADMIN AREA (MENU KEPENDUDUKAN)
// ===============================
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/kependudukan', [KependudukanController::class, 'index'])->name('menu.kependudukan');
    Route::get('/exportall', [ExportAllController::class, 'index'])->name('menu.exportall');

    // Routes untuk Keluarga
    Route::resource('keluarga', KeluargaController::class)->except(['show'])->names('dasar-keluarga');
    // AJAX Wilayah (bisa dipakai bersama)
    Route::get('/get-kabupaten/{kdprovinsi}', [KeluargaController::class, 'getKabupaten']);
    Route::get('/get-kecamatan/{kdkabupaten}', [KeluargaController::class, 'getKecamatan']);
    Route::get('/get-desa/{kdkecamatan}', [KeluargaController::class, 'getDesa']);

    // Kelompok route berdasarkan kategori
    $keluargaControllers = [
        'prasarana' => PrasaranaDasarController::class,
        'asetkeluarga' => AsetKeluargaController::class,
        'asetlahan' => AsetLahanController::class,
        'asetperikanan' => AsetPerikananController::class,
        'asetternak' => AsetTernakController::class,
        'sarpraskerja' => SarprasKerjaController::class,
        'bangunkeluarga' => BangunKeluargaController::class,
        'sejahterakeluarga' => SejahteraKeluargaController::class,
        'konfliksosial' => KonflikSosialController::class,
        'kualitasibuhamil' => KualitasIbuHamilController::class,
        'kualitasbayi' => KualitasBayiController::class,
    ];

    foreach ($keluargaControllers as $uri => $ctrl) {
        Route::controller($ctrl)->prefix($uri)->name("keluarga.$uri.")->group(function () use ($uri, $ctrl) {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
    }

    // Routes untuk Penduduk
    Route::resource('penduduk', PendudukController::class)->except(['show'])->names('dasar-penduduk');
    // AJAX Wilayah (bisa dipakai bersama)
    Route::get('/get-kabupaten/{kdprovinsi}', [PendudukController::class, 'getKabupaten']);
    Route::get('/get-kecamatan/{kdkabupaten}', [PendudukController::class, 'getKecamatan']);
    Route::get('/get-desa/{kdkecamatan}', [PendudukController::class, 'getDesa']);
    // Penduduk 
    $pendudukControllers = [
        'kelahiran' => KelahiranController::class,
        'sosialekonomi' => SosialEkonomiController::class,
        'usahaart' => UsahaArtController::class,
        'programserta' => ProgramSertaController::class,
        'lemdes' => LembagaDesaController::class,
        'lemmas' => LembagaMasyarakatController::class,
        'lembagaekonomi' => LembagaEkonomiController::class,
    ];

    foreach ($pendudukControllers as $uri => $ctrl) {
        Route::controller($ctrl)->prefix($uri)->name("penduduk.$uri.")->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
    }

    // Report PDF Laporan (Fixed duplicates & naming)
    Route::get('laporan/keluarga/pdf', [KeluargaController::class, 'exportPdf'])->name('keluarga.exportAnalisisPDF');
    Route::get('laporan/penduduk/pdf', [PendudukController::class, 'exportPdf'])->name('penduduk.exportAnalisisPDF');
    Route::get('laporan/kelahiran/pdf', [KelahiranController::class, 'exportPdf'])->name('kelahiran.exportAnalisisPDF');
    Route::get('laporan/asetkeluarga/pdf', [AsetKeluargaController::class, 'exportPdf'])->name('asetkeluarga.exportAnalisisPDF');
    Route::get('laporan/asetlahan/pdf', [AsetLahanController::class, 'exportPdf'])->name('asetlahan.exportAnalisisPDF');
    Route::get('laporan/prasarana/pdf', [PrasaranaDasarController::class, 'exportPdf'])->name('prasarana.exportAnalisisPDF');
    Route::get('laporan/sosialekonomi/pdf', [SosialEkonomiController::class, 'exportPdf'])->name('sosialekonomi.exportAnalisisPDF');
    Route::get('laporan/usahaart/pdf', [UsahaArtController::class, 'exportPdf'])->name('usahaart.exportAnalisisPDF');
    Route::get('laporan/programserta/pdf', [ProgramSertaController::class, 'exportPdf'])->name('programserta.exportAnalisisPDF');
    Route::get('laporan/lembagadesa/pdf', [LembagaDesaController::class, 'exportPdf'])->name('lembagadesa.exportAnalisisPDF');
    Route::get('laporan/lembagaekonomi/pdf', [LembagaEkonomiController::class, 'exportPdf'])->name('lembagaekonomi.exportAnalisisPDF');
    Route::get('laporan/lembagamasyarakat/pdf', [LembagaMasyarakatController::class, 'exportPdf'])->name('lembagamasyarakat.exportAnalisisPDF');
    Route::get('laporan/kualitasibuhamil/pdf', [KualitasIbuHamilController::class, 'exportPdf'])->name('kualitasibuhamil.exportAnalisisPDF');
    Route::get('laporan/kualitasbayi/pdf', [KualitasBayiController::class, 'exportPdf'])->name('kualitasbayi.exportAnalisisPDF');
    Route::get('laporan/sejahterakeluarga/pdf', [SejahteraKeluargaController::class, 'exportPdf'])->name('sejahterakeluarga.export.pdf');
    Route::get('laporan/konfliksosial/pdf', [KonflikSosialController::class, 'exportPdf'])->name('konfliksosial.export.pdf');
    Route::get('laporan/bangunkeluarga/pdf', [BangunKeluargaController::class, 'exportPdf'])->name('bangunkeluarga.export.pdf');
    Route::get('laporan/sarpraskerja/pdf', [SarprasKerjaController::class, 'exportPdf'])->name('sarpraskerja.export.pdf');
    Route::get('laporan/asetperikanan/pdf', [AsetPerikananController::class, 'exportPdf'])->name('asetperikanan.export.pdf');
    Route::get('laporan/asetternak/pdf', [AsetTernakController::class, 'exportPdf'])->name('asetternak.export.pdf');
});

Route::prefix('api')->group(function () {
    Route::get('/statistik-desa', function () {
        try {
            $keluarga = \App\Models\DataKeluarga::count();
            $penduduk = \App\Models\DataPenduduk::count();

            // Jenis Kelamin - aman tanpa relasi
            $laki = \App\Models\DataPenduduk::whereHas('jeniskelamin', function ($q) {
                $q->where('jeniskelamin', 'like', '%laki%'); // lebih aman
            })->count();

            $perempuan = \App\Models\DataPenduduk::whereHas('jeniskelamin', function ($q) {
                $q->where('jeniskelamin', 'like', '%perempuan%');
            })->count();


            // Dusun
            $dusun = \App\Models\DataKeluarga::selectRaw('kddusun, count(*) as jumlah')
                ->groupBy('kddusun')
                ->get()
                ->map(function ($item) {
                    $nama = 'Tidak Diketahui';
                    if ($item->kddusun) {
                        $dusunMaster = \App\Models\MasterDusun::find($item->kddusun);
                        $nama = $dusunMaster?->dusun ?? 'Tidak Diketahui';
                    }
                    return ['nama' => $nama, 'jumlah' => $item->jumlah];
                });

            // Usia - MySQL safe
            $today = now()->format('Y-m-d');
            $usia = [
                'anak' => \App\Models\DataPenduduk::whereRaw("TIMESTAMPDIFF(YEAR, penduduk_tanggallahir, ?) < 15", [$today])->count(),
                'produktif' => \App\Models\DataPenduduk::whereRaw("TIMESTAMPDIFF(YEAR, penduduk_tanggallahir, ?) BETWEEN 15 AND 64", [$today])->count(),
                'lansia' => \App\Models\DataPenduduk::whereRaw("TIMESTAMPDIFF(YEAR, penduduk_tanggallahir, ?) > 64", [$today])->count(),
            ];

            // Agama
            $agama = \App\Models\DataPenduduk::selectRaw('kdagama, count(*) as jumlah')
                ->groupBy('kdagama')
                ->get()
                ->map(function ($item) {
                    $nama = 'Tidak Diketahui';
                    if ($item->kdagama) {
                        $agamaMaster = \App\Models\MasterAgama::find($item->kdagama);
                        $nama = $agamaMaster?->agama ?? 'Tidak Diketahui';
                    }
                    return ['nama' => $nama, 'jumlah' => $item->jumlah];
                });

            // Status Perkawinan
            $status_kawin = \App\Models\DataPenduduk::selectRaw('kdstatuskawin, count(*) as jumlah')
                ->groupBy('kdstatuskawin')
                ->get()
                ->map(function ($item) {
                    $nama = 'Tidak Diketahui';
                    if ($item->kdstatuskawin) {
                        $master = \App\Models\MasterStatusKawin::find($item->kdstatuskawin);
                        $nama = $master?->statuskawin ?? 'Tidak Diketahui';
                    }
                    return ['nama' => $nama, 'jumlah' => $item->jumlah];
                });

            return response()->json([
                'keluarga' => $keluarga,
                'penduduk' => $penduduk,
                'laki' => $laki,
                'perempuan' => $perempuan,
                'dusun' => $dusun,
                'usia' => $usia,
                'agama' => $agama,
                'status_kawin' => $status_kawin,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error statistik-desa: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data', 'message' => $e->getMessage()], 500);
        }
    });

    Route::prefix('kelembagaan')->name('kelembagaan.')->group(function () {

        /* =========================
       PENGURUS
    ========================= */
        Route::get('/pengurus', [PengurusKelembagaanController::class, 'index'])->name('pengurus.index');
        Route::get('/pengurus/create', [PengurusKelembagaanController::class, 'create'])->name('pengurus.create');
        Route::post('/pengurus', [PengurusKelembagaanController::class, 'store'])->name('pengurus.store');
        Route::get('/pengurus/{id}', [PengurusKelembagaanController::class, 'show'])->name('pengurus.show');
        Route::get('/pengurus/{id}/edit', [PengurusKelembagaanController::class, 'edit'])->name('pengurus.edit');
        Route::put('/pengurus/{id}', [PengurusKelembagaanController::class, 'update'])->name('pengurus.update');
        Route::delete('/pengurus/{id}', [PengurusKelembagaanController::class, 'destroy'])->name('pengurus.destroy');


        /* =========================
       KEPUTUSAN
    ========================= */
        Route::get('/keputusan/export-pdf', [KeputusanController::class, 'exportPdf'])
            ->name('keputusan.exportPdf');

        Route::get('/keputusan/export', [KeputusanController::class, 'export'])
            ->name('keputusan.export');

        Route::get('/keputusan', [KeputusanController::class, 'index'])->name('keputusan.index');
        Route::get('/keputusan/create', [KeputusanController::class, 'create'])->name('keputusan.create');
        Route::post('/keputusan', [KeputusanController::class, 'store'])->name('keputusan.store');
        Route::get('/keputusan/{id}', [KeputusanController::class, 'show'])->name('keputusan.show');
        Route::get('/keputusan/{id}/edit', [KeputusanController::class, 'edit'])->name('keputusan.edit');
        Route::put('/keputusan/{id}', [KeputusanController::class, 'update'])->name('keputusan.update');
        Route::delete('/keputusan/{id}', [KeputusanController::class, 'destroy'])->name('keputusan.destroy');

        /* =========================
       KEGIATAN
    ========================= */
        Route::get('/kegiatan/export-pdf', [KegiatanController::class, 'exportPdf'])
            ->name('kegiatan.exportPdf');
        Route::get('/kegiatan/export', [KegiatanController::class, 'export'])
            ->name('kegiatan.export');
        Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
        Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
        Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
        Route::get('/kegiatan/{id}', [KegiatanController::class, 'show'])->name('kegiatan.show');
        Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
        Route::put('/kegiatan/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');
        Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');


        /* =========================
       AGENDA
    ========================= */
        Route::get('/agenda/export', [AgendaKelembagaanController::class, 'export'])
            ->name('agenda.export');

        Route::get('/agenda/export-pdf', [AgendaKelembagaanController::class, 'exportPdf'])
            ->name('agenda.exportPdf');
        Route::get('/agenda', [AgendaKelembagaanController::class, 'index'])->name('agenda.index');
        Route::get('/agenda/create', [AgendaKelembagaanController::class, 'create'])->name('agenda.create');
        Route::post('/agenda', [AgendaKelembagaanController::class, 'store'])->name('agenda.store');
        Route::get('/agenda/{id}', [AgendaKelembagaanController::class, 'show'])->name('agenda.show');
        Route::get('/agenda/{id}/edit', [AgendaKelembagaanController::class, 'edit'])->name('agenda.edit');
        Route::put('/agenda/{id}', [AgendaKelembagaanController::class, 'update'])->name('agenda.update');
        Route::delete('/agenda/{id}', [AgendaKelembagaanController::class, 'destroy'])->name('agenda.destroy');
        // ======================
        // ANGGARAN KELEMBAGAAN
        // ======================
        Route::get('/anggaran/export', [AnggaranKelembagaanController::class, 'export'])
            ->name('anggaran.export');

        Route::get('/anggaran/export-pdf', [AnggaranKelembagaanController::class, 'exportPdf'])
            ->name('anggaran.exportPdf');
        Route::get('/anggaran', [AnggaranKelembagaanController::class, 'index'])->name('anggaran.index');
        Route::get('/anggaran/create', [AnggaranKelembagaanController::class, 'create'])->name('anggaran.create');
        Route::post('/anggaran', [AnggaranKelembagaanController::class, 'store'])->name('anggaran.store');
        Route::get('/anggaran/{id}', [AnggaranKelembagaanController::class, 'show'])->name('anggaran.show');
        Route::get('/anggaran/{id}/edit', [AnggaranKelembagaanController::class, 'edit'])->name('anggaran.edit');
        Route::put('/anggaran/{id}', [AnggaranKelembagaanController::class, 'update'])->name('anggaran.update');
        Route::delete('/anggaran/{id}', [AnggaranKelembagaanController::class, 'destroy'])->name('anggaran.destroy');

        // ======================
        // ANGGARAN PER KEGIATAN
        // ======================
        Route::get('/kegiatan-anggaran', [KegiatanAnggaranController::class, 'index'])->name('kegiatan_anggaran.index');
        Route::get('/kegiatan-anggaran/create', [KegiatanAnggaranController::class, 'create'])->name('kegiatan_anggaran.create');
        Route::post('/kegiatan-anggaran', [KegiatanAnggaranController::class, 'store'])->name('kegiatan_anggaran.store');
        Route::get('/kegiatan-anggaran/{id}', [KegiatanAnggaranController::class, 'show'])->name('kegiatan_anggaran.show');
        Route::delete('/kegiatan-anggaran/{id}', [KegiatanAnggaranController::class, 'destroy'])->name('kegiatan_anggaran.destroy');

        // ======================
        // PENCAIRAN DANA
        // ======================

        Route::get('/pencairan/export', [PencairanDanaController::class, 'export'])
            ->name('pencairan.export');

        Route::get('/pencairan/export-pdf', [PencairanDanaController::class, 'exportPdf'])
            ->name('pencairan.exportPdf');
        Route::get('/pencairan', [PencairanDanaController::class, 'index'])->name('pencairan.index');
        Route::get('/pencairan/create', [PencairanDanaController::class, 'create'])->name('pencairan.create');
        Route::post('/pencairan', [PencairanDanaController::class, 'store'])->name('pencairan.store');
        Route::get('/pencairan/{id}', [PencairanDanaController::class, 'show'])->name('pencairan.show');
        Route::delete('/pencairan/{id}', [PencairanDanaController::class, 'destroy'])
            ->name('pencairan.destroy');
        Route::get('/pencairan/{id}/edit', [PencairanDanaController::class, 'edit'])
            ->name('pencairan.edit');

        Route::put('/pencairan/{id}', [PencairanDanaController::class, 'update'])
            ->name('pencairan.update');
        // ======================
        // REALISASI PENGELUARAN
        // ======================
        Route::post('/realisasi', [RealisasiPengeluaranController::class, 'store'])->name('realisasi.store');
        Route::delete('/realisasi/{id}', [RealisasiPengeluaranController::class, 'destroy'])->name('realisasi.destroy');
        Route::get(
            '/realisasi/create/{pencairan}',
            [RealisasiPengeluaranController::class, 'create']
        )->name('realisasi.create');
        Route::put('/realisasi', [RealisasiPengeluaranController::class, 'update'])
            ->name('realisasi.update');
        Route::delete('/realisasi/{id}', [RealisasiPengeluaranController::class, 'destroy'])->name('realisasi.destroy');
        // ======================
        // LPJ KEGIATAN
        // ======================
        Route::get('/lpj/export', [LPJKegiatanController::class, 'export'])->name('lpj.export');
        Route::get('/lpj/export-pdf', [LPJKegiatanController::class, 'exportPdf'])->name('lpj.exportPdf');
        Route::get('/lpj', [LpjKegiatanController::class, 'index'])->name('lpj.index');
        Route::get('/lpj/create', [LpjKegiatanController::class, 'create'])->name('lpj.create');
        Route::post('/lpj', [LpjKegiatanController::class, 'store'])->name('lpj.store');
        Route::get('/lpj/{id}', [LpjKegiatanController::class, 'show'])->name('lpj.show');
        Route::put('/lpj/{id}', [LpjKegiatanController::class, 'update'])->name('lpj.update');
        Route::get('/lpj/{id}/edit', [LpjKegiatanController::class, 'edit'])->name('lpj.edit');
        Route::put('/lpj/{id}', [LpjKegiatanController::class, 'update'])->name('lpj.update');
        Route::delete('/lpj/{id}', [LpjKegiatanController::class, 'destroy'])->name('lpj.destroy');

        Route::get(
            '/kelembagaan/pengurus/export',
            [PengurusKelembagaanController::class, 'exportExcel']
        )->name('pengurus.export');
        Route::get('/export/pdf', [PengurusKelembagaanController::class, 'exportPdf'])
            ->name('pengurus.exportPdf');
    });

    Route::prefix('voice')->name('voice.')->group(function () {
        Route::get('/pembangunan', [VoicePembangunanController::class, 'index'])
            ->name('pembangunan.index');

        Route::post('/pembangunan/store-all', [VoicePembangunanController::class, 'storeAll'])
            ->name('pembangunan.store-all');
        Route::get('/kelembagaan', [VoiceKelembagaanController::class, 'index'])
            ->name('kelembagaan.index');
        Route::post('/pelayanan/surat', [VoicePelayananController::class, 'store'])
            ->name('pelayanan.surat');
        Route::get('/pelayanan/surat', [VoicePelayananController::class, 'index'])
            ->name('pelayanan.index');
        Route::post('/kelembagaan/store-all', [VoiceKelembagaanController::class, 'storeAll'])
            ->name('kelembagaan.store-all');
        Route::get('/admin-umum', [VoiceUmumController::class, 'index'])
            ->name('admin-umum.index');

        Route::post('/admin-umum/store', [VoiceUmumController::class, 'storeAll'])
            ->name('admin-umum.store');

        Route::get('/kelembagaan/{id}', [VoiceKelembagaanController::class, 'show'])
            ->name('kelembagaan.show');
    });

    Route::prefix('pelayanan')->name('pelayanan.')->middleware('auth')->group(function () {

        Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
        Route::get('/surat/create', [SuratController::class, 'create'])->name('surat.create');
        Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
        Route::get('/surat/{id}', [SuratController::class, 'show'])->name('surat.show');

        Route::post('/surat/{id}/approve', [SuratController::class, 'approve'])->name('surat.approve');
        Route::post('/surat/{id}/reject', [SuratController::class, 'reject'])->name('surat.reject');

        // DOWNLOAD PDF
        Route::get('/surat/{token}/download', [SuratController::class, 'download'])
            ->name('surat.download');

        Route::get('/surat/cetak/{token}', [SuratController::class, 'preview'])
            ->name('surat.cetak');

        Route::get('/surat/print/{token}', [SuratController::class, 'print'])
            ->name('surat.print');
        Route::get('/surat/preview/{id}', [SuratController::class, 'previewById'])
            ->name('surat.preview');
    });

    Route::prefix('admin-umum')
        ->middleware(['web', 'auth'])
        ->name('admin-umum.')
        ->group(function () {
            // EXPORT
            Route::get('/peraturan/export', [BukuPeraturanController::class, 'export'])
                ->name('peraturan.export');

            Route::get('/peraturan/export-pdf', [BukuPeraturanController::class, 'exportPdf'])
                ->name('peraturan.exportPdf');
            // ================= PERATURAN =================
            Route::get('/peraturan', [BukuPeraturanController::class, 'index'])->name('peraturan.index');
            Route::get('/peraturan/create', [BukuPeraturanController::class, 'create'])->name('peraturan.create');
            Route::post('/peraturan', [BukuPeraturanController::class, 'store'])->name('peraturan.store');
            Route::get('/peraturan/{id}', [BukuPeraturanController::class, 'show'])->name('peraturan.show');
            Route::get('/peraturan/{id}/edit', [BukuPeraturanController::class, 'edit'])->name('peraturan.edit');
            Route::put('/peraturan/{id}', [BukuPeraturanController::class, 'update'])->name('peraturan.update');
            Route::delete('/peraturan/{id}', [BukuPeraturanController::class, 'destroy'])->name('peraturan.destroy');

            // ================= KEPUTUSAN =================
            Route::get('/keputusan/export', [BukuKeputusanController::class, 'export'])->name('keputusan.export');
            Route::get('/keputusan/export-pdf', [BukuKeputusanController::class, 'exportPdf'])->name('keputusan.exportPdf');

            Route::get('/keputusan', [BukuKeputusanController::class, 'index'])->name('keputusan.index');
            Route::get('/keputusan/create', [BukuKeputusanController::class, 'create'])->name('keputusan.create');
            Route::post('/keputusan', [BukuKeputusanController::class, 'store'])->name('keputusan.store');
            Route::get('/keputusan/{id}', [BukuKeputusanController::class, 'show'])->name('keputusan.show');
            Route::get('/keputusan/{id}/edit', [BukuKeputusanController::class, 'edit'])->name('keputusan.edit');
            Route::put('/keputusan/{id}', [BukuKeputusanController::class, 'update'])->name('keputusan.update');
            Route::delete('/keputusan/{id}', [BukuKeputusanController::class, 'destroy'])->name('keputusan.destroy');

            // ================= AGENDA =================
            Route::get('agenda/export', [BukuAgendaLembagaController::class, 'export'])
                ->name('agenda.export');

            Route::get('agenda/export-pdf', [BukuAgendaLembagaController::class, 'exportPdf'])
                ->name('agenda.exportPdf');
            Route::get('/agenda_kelembagaan', [BukuAgendaLembagaController::class, 'index'])->name('agenda.index');
            Route::get('/agenda_kelembagaan/create', [BukuAgendaLembagaController::class, 'create'])->name('agenda.create');
            Route::post('/agenda_kelembagaan', [BukuAgendaLembagaController::class, 'store'])->name('agenda.store');
            Route::get('/agenda_kelembagaan/{id}', [BukuAgendaLembagaController::class, 'show'])->name('agenda.show');
            Route::get('/agenda_kelembagaan/{id}/edit', [BukuAgendaLembagaController::class, 'edit'])->name('agenda.edit');
            Route::put('/agenda_kelembagaan/{id}', [BukuAgendaLembagaController::class, 'update'])->name('agenda.update');
            Route::delete('/agenda_kelembagaan/{id}', [BukuAgendaLembagaController::class, 'destroy'])->name('agenda.destroy');

            // ================= APARAT =================
            Route::get('/aparat/export', [BukuAparatController::class, 'export'])
                ->name('aparat.export');

            Route::get('/aparat/export-pdf', [BukuAparatController::class, 'exportPdf'])
                ->name('aparat.exportPdf');
            Route::get('/aparat', [BukuAparatController::class, 'index'])->name('aparat.index');
            Route::get('/aparat/create', [BukuAparatController::class, 'create'])->name('aparat.create');
            Route::post('/aparat', [BukuAparatController::class, 'store'])->name('aparat.store');
            Route::get('/aparat/{id}', [BukuAparatController::class, 'show'])->name('aparat.show');
            Route::get('/aparat/{id}/edit', [BukuAparatController::class, 'edit'])->name('aparat.edit');
            Route::put('/aparat/{id}', [BukuAparatController::class, 'update'])->name('aparat.update');
            Route::delete('/aparat/{id}', [BukuAparatController::class, 'destroy'])->name('aparat.destroy');

            // ================= TANAH DESA =================
            Route::get('tanahdesa/export', [BukuTanahDesaController::class, 'export'])
                ->name('tanahdesa.export');
            Route::get('tanahdesa/export-pdf', [BukuTanahDesaController::class, 'exportPdf'])
                ->name('tanahdesa.exportPdf');
            Route::get('/tanah-desa', [BukuTanahDesaController::class, 'index'])->name('tanahdesa.index');
            Route::get('/tanah-desa/create', [BukuTanahDesaController::class, 'create'])->name('tanahdesa.create');
            Route::post('/tanah-desa', [BukuTanahDesaController::class, 'store'])->name('tanahdesa.store');
            Route::get('/tanah-desa/{id}', [BukuTanahDesaController::class, 'show'])->name('tanahdesa.show');
            Route::get('/tanah-desa/{id}/edit', [BukuTanahDesaController::class, 'edit'])->name('tanahdesa.edit');
            Route::put('/tanah-desa/{id}', [BukuTanahDesaController::class, 'update'])->name('tanahdesa.update');
            Route::delete('/tanah-desa/{id}', [BukuTanahDesaController::class, 'destroy'])->name('tanahdesa.destroy');

            // ================= TANAH KAS DESA =================

            // EXPORT EXCEL
            Route::get('tanahkasdesa/export', [BukuTanahKasDesaController::class, 'export'])
                ->name('tanahkasdesa.export');

            // EXPORT PDF
            Route::get('tanahkasdesa/export-pdf', [BukuTanahKasDesaController::class, 'exportPdf'])
                ->name('tanahkasdesa.exportPdf');
            Route::get('/tanah-kas-desa', [BukuTanahKasDesaController::class, 'index'])->name('tanahkasdesa.index');
            Route::get('/tanah-kas-desa/create', [BukuTanahKasDesaController::class, 'create'])->name('tanahkasdesa.create');
            Route::post('/tanah-kas-desa', [BukuTanahKasDesaController::class, 'store'])->name('tanahkasdesa.store');
            Route::get('/tanah-kas-desa/{id}', [BukuTanahKasDesaController::class, 'show'])->name('tanahkasdesa.show');
            Route::get('/tanah-kas-desa/{id}/edit', [BukuTanahKasDesaController::class, 'edit'])->name('tanahkasdesa.edit');
            Route::put('/tanah-kas-desa/{id}', [BukuTanahKasDesaController::class, 'update'])->name('tanahkasdesa.update');
            Route::delete('/tanah-kas-desa/{id}', [BukuTanahKasDesaController::class, 'destroy'])->name('tanahkasdesa.destroy');

            // ================= EKSPEDISI =================
            Route::get('/admin-umum/ekspedisi/export-excel', [BukuEkspedisiController::class, 'exportExcel'])->name('ekspedisi.exportExcel');
            Route::get('/admin-umum/ekspedisi/export-pdf', [BukuEkspedisiController::class, 'exportPdf'])->name('ekspedisi.exportPdf');
            Route::get('/ekspedisi', [BukuEkspedisiController::class, 'index'])->name('ekspedisi.index');
            Route::get('/ekspedisi/create', [BukuEkspedisiController::class, 'create'])->name('ekspedisi.create');
            Route::post('/ekspedisi', [BukuEkspedisiController::class, 'store'])->name('ekspedisi.store');
            Route::get('/ekspedisi/{id}', [BukuEkspedisiController::class, 'show'])->name('ekspedisi.show');
            Route::get('/ekspedisi/{id}/edit', [BukuEkspedisiController::class, 'edit'])->name('ekspedisi.edit');
            Route::put('/ekspedisi/{id}', [BukuEkspedisiController::class, 'update'])->name('ekspedisi.update');
            Route::delete('/ekspedisi/{id}', [BukuEkspedisiController::class, 'destroy'])->name('ekspedisi.destroy');

            // ================= INVENTARIS =================
            Route::get('admin-umum/inventaris/export', [BukuInventarisController::class, 'export'])
                ->name('inventaris.export');

            Route::get('admin-umum/inventaris/export-pdf', [BukuInventarisController::class, 'exportPdf'])
                ->name('inventaris.exportPdf');
            Route::get('/inventaris', [BukuInventarisController::class, 'index'])->name('inventaris.index');
            Route::get('/inventaris/create', [BukuInventarisController::class, 'create'])->name('inventaris.create');
            Route::post('/inventaris', [BukuInventarisController::class, 'store'])->name('inventaris.store');
            Route::get('/inventaris/{id}', [BukuInventarisController::class, 'show'])->name('inventaris.show');
            Route::get('/inventaris/{id}/edit', [BukuInventarisController::class, 'edit'])->name('inventaris.edit');
            Route::put('/inventaris/{id}', [BukuInventarisController::class, 'update'])->name('inventaris.update');
            Route::delete('/inventaris/{id}', [BukuInventarisController::class, 'destroy'])->name('inventaris.destroy');
        });

    Route::prefix('admin-pembangunan')
        ->middleware(['web', 'auth'])
        ->name('admin-pembangunan.')
        ->group(function () {

            // ================= BUKU BANTUAN =================
            Route::get('bantuan/export', [BukuBantuanController::class, 'export'])
                ->name('bantuan.export');
            Route::get('bantuan/export-pdf', [BukuBantuanController::class, 'exportPdf'])
                ->name('bantuan.exportPdf');

            Route::get('/bantuan', [BukuBantuanController::class, 'index'])->name('bantuan.index');
            Route::get('/bantuan/create', [BukuBantuanController::class, 'create'])->name('bantuan.create');
            Route::post('/bantuan', [BukuBantuanController::class, 'store'])->name('bantuan.store');
            Route::get('/bantuan/{id}', [BukuBantuanController::class, 'show'])->name('bantuan.show');
            Route::get('/bantuan/{id}/edit', [BukuBantuanController::class, 'edit'])->name('bantuan.edit');
            Route::put('/bantuan/{id}', [BukuBantuanController::class, 'update'])->name('bantuan.update');
            Route::delete('/bantuan/{id}', [BukuBantuanController::class, 'destroy'])->name('bantuan.destroy');

            // ================= BUKU KADER =================
            Route::get('kader/export', [BukuKaderController::class, 'export'])
                ->name('kader.export');
            Route::get('kader/export-pdf', [BukuKaderController::class, 'exportPdf'])
                ->name('kader.exportPdf');

            Route::get('/kader', [BukuKaderController::class, 'index'])->name('kader.index');
            Route::get('/kader/create', [BukuKaderController::class, 'create'])->name('kader.create');
            Route::post('/kader', [BukuKaderController::class, 'store'])->name('kader.store');
            Route::get('/kader/{id}', [BukuKaderController::class, 'show'])->name('kader.show');
            Route::get('/kader/{id}/edit', [BukuKaderController::class, 'edit'])->name('kader.edit');
            Route::put('/kader/{id}', [BukuKaderController::class, 'update'])->name('kader.update');
            Route::delete('/kader/{id}', [BukuKaderController::class, 'destroy'])->name('kader.destroy');

            // ================= BUKU PROYEK =================
            Route::get('proyek/export', [BukuProyekController::class, 'export'])
                ->name('proyek.export');
            Route::get('proyek/export-pdf', [BukuProyekController::class, 'exportPdf'])
                ->name('proyek.exportPdf');

            Route::get('/proyek', [BukuProyekController::class, 'index'])->name('proyek.index');
            Route::get('/proyek/create', [BukuProyekController::class, 'create'])->name('proyek.create');
            Route::post('/proyek', [BukuProyekController::class, 'store'])->name('proyek.store');
            Route::get('/proyek/{id}', [BukuProyekController::class, 'show'])->name('proyek.show');
            Route::get('/proyek/{id}/edit', [BukuProyekController::class, 'edit'])->name('proyek.edit');
            Route::put('/proyek/{id}', [BukuProyekController::class, 'update'])->name('proyek.update');
            Route::delete('/proyek/{id}', [BukuProyekController::class, 'destroy'])->name('proyek.destroy');
        });

    /* =========================
    VERIFIKASI QR (PUBLIC)
    ========================= */

    Route::get('/surat/verifikasi/{kode}', [SuratController::class, 'verifikasi'])->name('surat.verifikasi');
});
Route::get('/voice/kegiatan/{id}/sisa', function ($id) {
    $kegiatan = \App\Models\kegiatan::findOrFail($id);

    $sudah = $kegiatan->pencairanDana()->sum('jumlah');
    $sisa = $kegiatan->pagu_anggaran - $sudah;

    return response()->json([
        'pagu' => $kegiatan->pagu_anggaran,
        'sudah' => $sudah,
        'sisa' => $sisa
    ]);
});
Route::get('/pelayanan/surat/cek-nik', [SuratController::class, 'cekNik'])
    ->name('pelayanan.surat.cek-nik');

// ===============================
// AUTH
// ===============================
require __DIR__ . '/auth.php';
