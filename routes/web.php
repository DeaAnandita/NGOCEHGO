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
    VoiceKeluargaController, WilayahController
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
<<<<<<< HEAD
Route::prefix('export')->group(function () {
    Route::get('aset-keluarga', fn() => DataAsetKeluargaExport::export())->name('export.asetkeluarga');
    Route::get('data-keluarga', fn() => DataKeluargaExport::export())->name('export.datakeluarga');
    Route::get('data-keluarga-pdf', fn() => DataKeluargaExport::export())->name('datakeluarga.export.pdf');
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
=======
use App\Exports\DataAsetKeluargaExport;

Route::get('/export-aset-keluarga', function () {
    return DataAsetKeluargaExport::export();
})->name('export.asetkeluarga');

// routes/web.php
use App\Exports\DataKeluargaExport;
use App\Exports\DataAsetKeluargaPdfExport;
use App\Exports\DataLembagaEkonomiExport;
use App\Exports\DataLembagamasyarakatExport;

Route::get('/export/data-keluarga', function () {
    return DataKeluargaExport::export();
})->name('export.datakeluarga');

use App\Exports\DataKeluargaPdfExport;

Route::get('/export-datakeluarga-pdf', function () {
    return DataKeluargaPdfExport::export();
})->name('datakeluarga.export.pdf');

//prasarana
use App\Exports\DataPrasaranaExport;

Route::get('/export/data-prasarana', function () {
    return DataPrasaranaExport::export();
})->name('export.dataprasarana');

use App\Exports\DataPrasaranaPdfExport;

// Route::get('/export-dataprasarana-pdf', function () {
//     return DataPrasaranaPdfExport::export();
// })->name('dataprasarana.export.pdf');

//bela
use App\Exports\DataSejahteraKeluargaExport;

Route::get('/export-sejahtera-keluarga', function () {
    return DataSejahteraKeluargaExport::export();
})->name('export.sejahterakeluarga');

//bela
use App\Exports\DataKonflikSosialExport;

Route::get('/export-konflik-sosial', function () {
    return DataKonflikSosialExport::export();
})->name('export.konfliksosial');


use App\Exports\DataPendudukExport;

Route::get('/export-penduduk', function () {
    return DataPendudukExport::export();
})->name('export.penduduk');

use App\Exports\DataPendudukPdfExport;

use App\Exports\DataKelahiranExport;

Route::get('/export-kelahiran', function () {
    return DataKelahiranExport::export();
})->name('export.kelahiran');

use App\Exports\DataKelahiranPdfExport;

use App\Exports\DataSosialEkonomiExport;

Route::get('/export-sosial-ekonomi', function () {
    return DataSosialEkonomiExport::export();
})->name('export.sosialekonomi');


use App\Exports\DataUsahaArtExport;

Route::get('/export-usaha-art', function () {
    return DataUsahaArtExport::export();
})->name('export.usahaart');


use App\Exports\DataProgramSertaExport;

Route::get('/export-programserta', function () {
    return DataProgramSertaExport::export();
})->name('export.programserta');

use App\Exports\DataLembagaDesaExport;

Route::get('/export-lembagadesa', function () {
    return DataLembagaDesaExport::export();
})->name('export.lembagadesa');

Route::get('/export-lembaga-ekonomi', function ()  {
    return DataLembagaEkonomiExport::export();
})->name('export.lembagaekonomi');

Route::get('/export-lembaga-masyarakat', function ()  {
    return DataLembagaMasyarakatExport::export();
})->name('export.lembagamasyarakat');



//rema
Route::get('/export/kualitas-ibu-hamil', [DataKualitasIbuHamilExport::class, 'export'])
    ->name('export.kualitasibuhamil');

use App\Exports\DataKualitasBayiExport;

Route::get('/export/kualitas-bayi', [DataKualitasBayiExport::class, 'export'])
    ->name('export.kualitasbayi');

//report aset ternak
use App\Exports\DataAsetTernakExport;

Route::get('/export-aset-ternak', function () {
    return DataAsetTernakExport::export();
})->name('export.asetternak');
//report aset perikanan
use App\Exports\DataAsetPerikananExport;

Route::get('/export-aset-perikanan', function () {
    return DataAsetPerikananExport::export();
})->name('export.asetperikanan');

use App\Exports\DataAsetPerikananPdfExport;

//Sofi
use App\Exports\DataSarprasKerjaExport;

Route::get('/export-sarpras-kerja', function () {
    return DataSarprasKerjaExport::export();
})->name('export.sarpraskerja');

use App\Exports\DataSarprasKerjaPdfExport;

Route::get('/laporan/sarpraskerja/pdf', [App\Http\Controllers\SarprasKerjaController::class, 'exportPdf'])
    ->name('sarpraskerja.export.pdf');

//Sofi
use App\Exports\DataBangunKeluargaExport;
use App\Models\DataBangunKeluarga;

Route::get('/export-bangun-keluarga', function () {
    return DataBangunKeluargaExport::export();
})->name('export.bangunkeluarga');
>>>>>>> 50f2f57 (export excel)

use App\Exports\DataBangunKeluargaPdfExport;

Route::get('/laporan/bangunkeluarga/pdf', [App\Http\Controllers\BangunKeluargaController::class, 'exportPdf'])
    ->name('bangunkeluarga.export.pdf');

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
    Route::get('laporan/asetkeluarga/pdf', [AsetKeluargaController::class, 'exportPdf'])->name('asetkeluarga.exportAnalisisPDF');
    Route::get('/laporan/sosialekonomi/pdf', [App\Http\Controllers\SosialEkonomiController::class, 'exportPdf'])->name('sosialekonomi.exportAnalisisPDF');
    Route::get('/laporan/usahaart/pdf', [App\Http\Controllers\UsahaArtController::class, 'exportPdf'])->name('usahaart.exportAnalisisPDF');
    Route::get('/asetperikanan/export/pdf', [AsetPerikananController::class, 'exportPdf'])->name('asetperikanan.export.pdf');
    Route::get('/asetternak/export/pdf', [AsetTernakController::class, 'exportPdf'])->name('asetternak.export.pdf');
});

// ===============================
// AUTH
// ===============================
require __DIR__ . '/auth.php';