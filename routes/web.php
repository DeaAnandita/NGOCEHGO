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
use App\Http\Controllers\{
    VoiceKeluargaController,
    VoicePrasaranaController,
    VoicePendudukController,
    VoiceValidationController
};
use App\Exports\{
    DataKualitasIbuHamilExport, DataKeluargaExport, DataLembagaEkonomiExport, DataLembagamasyarakatExport,
    DataAsetKeluargaExport, DataPrasaranaExport, DataSejahteraKeluargaExport, DataKonflikSosialExport,
    DataPendudukExport, DataKelahiranExport, DataSosialEkonomiExport, DataUsahaArtExport,
    DataProgramSertaExport, DataLembagaDesaExport, DataKualitasBayiExport,
    DataAsetTernakExport, DataAsetPerikananExport, DataSarprasKerjaExport, DataBangunKeluargaExport
};


Route::get('/admin/voice', [VoiceKeluargaController::class, 'index'])->name('voice.menu');

Route::prefix('admin/voice')->group(function () {
    Route::get('/keluarga', [VoiceKeluargaController::class, 'keluarga'])->name('voice.keluarga');
    
    Route::post('/session', [VoiceKeluargaController::class, 'createSession'])->name('voice.session');
    Route::post('/answer', [VoiceKeluargaController::class, 'storeAnswer'])->name('voice.answer');
    Route::post('/final-save', [VoiceKeluargaController::class, 'finalSave'])->name('voice.final-save');
    
    Route::post('/keluarga/store', [VoiceKeluargaController::class, 'store'])->name('voice.keluarga.store');

    // TAMBAHAN: untuk cek duplikat suara
    Route::post('/check-duplicate', [VoiceKeluargaController::class, 'checkDuplicate'])
         ->name('voice.check-duplicate');
         Route::post('/upload-voice', [VoiceKeluargaController::class, 'uploadVoice'])->name('voice.upload');


    // Dynamic wilayah (pastikan ada!)
    Route::get('/get-kabupaten/{id}', [VoiceKeluargaController::class, 'getKabupaten']);
    Route::get('/get-kecamatan/{id}', [VoiceKeluargaController::class, 'getKecamatan']);
    Route::get('/get-desa/{id}', [VoiceKeluargaController::class, 'getDesa']);
});


Route::get('/get-kabupaten/{kdprovinsi}', [WilayahController::class, 'getKabupaten']);
Route::get('/get-kecamatan/{kdkabupaten}', [WilayahController::class, 'getKecamatan']);
Route::get('/get-desa/{kdkecamatan}', [WilayahController::class, 'getDesa']);


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
    Route::get('data-keluarga', fn() => DataKeluargaExport::export())->name('export.datakeluarga');
    Route::get('data-keluarga-pdf', fn() => DataKeluargaPdfExport::export())->name('datakeluarga.export.pdf');
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

    // Report PDF laporan (Keluarga)
    Route::get('laporan/asetkeluarga/pdf', [AsetKeluargaController::class, 'exportPdf'])
        ->name('asetkeluarga.exportAnalisisPDF');

    // 🎤 Voice Input Mode (Aset Keluarga)
    Route::get('asetkeluarga/voice', [AsetKeluargaController::class, 'voiceInput'])
        ->name('keluarga.asetkeluarga.voice');

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

    // Report PDF Laporan (Penduduk/Warga)
    
});

// ===============================
// AUTH
// ===============================
require __DIR__ . '/auth.php';