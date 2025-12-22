<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController, MasterController, KependudukanController,
    KeluargaController, PendudukController, PrasaranaDasarController, AsetKeluargaController, AsetLahanController,
    AsetPerikananController, AsetTernakController, SarprasKerjaController, BangunKeluargaController,
    SejahteraKeluargaController, KonflikSosialController, KualitasIbuHamilController, KualitasBayiController,
    KelahiranController, SosialEkonomiController, UsahaArtController, ProgramSertaController,
    LembagaDesaController, LembagaMasyarakatController, LembagaEkonomiController
};
use App\Http\Controllers\Voice\{
    VoiceKeluargaController, WilayahController, VoicePrasaranaController, MenuVoiceController, VoiceController,
    VoicePendudukController,VoiceFingerprintController
};
use App\Exports\{
    DataKualitasIbuHamilExport, DataKeluargaExport, DataLembagaEkonomiExport, DataLembagamasyarakatExport,
    DataAsetKeluargaExport, DataPrasaranaExport, DataSejahteraKeluargaExport, DataKonflikSosialExport,
    DataPendudukExport, DataKelahiranExport, DataSosialEkonomiExport, DataUsahaArtExport,
    DataProgramSertaExport, DataLembagaDesaExport, DataKualitasBayiExport, DataAsetLahanExport,
    DataAsetTernakExport, DataAsetPerikananExport, DataSarprasKerjaExport, DataBangunKeluargaExport
};


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
    Route::post('/check-voice-duplicate', [VoiceFingerprintController::class, 'checkDuplicate']);
    Route::post('/keluarga/store-all', [VoiceKeluargaController::class, 'storeAll'])
        ->name('keluarga.store-all'); // ← ROUTE KHUSUS KELUARGA

    // -----------------------------
    // Voice Penduduk
    // -----------------------------
    Route::get('/penduduk', [VoicePendudukController::class, 'index'])->name('penduduk.index');
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

// ===============================
// AUTH
// ===============================
require __DIR__ . '/auth.php';