<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\PrasaranaDasarController;
use App\Http\Controllers\KependudukanController;
use App\Http\Controllers\{
    AsetKeluargaController, AsetLahanController, AsetPerikananController, 
    AsetTernakController, SarprasKerjaController, BangunKeluargaController, 
    SejahteraKeluargaController, KonflikSosialController, KualitasIbuHamilController, KualitasBayiController,
    KelahiranController, SosialEkonomiController, UsahaArtController, ProgramSertaController, 
    LembagaDesaController,LembagaMasyarakatController, LembagaEkonomiController};

use App\Exports\DataKualitasIbuHamilExport;
use Illuminate\Support\Facades\Route;

// ===============================
// HALAMAN AWAL
// ===============================
Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ===============================
// PROFILE
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===============================
// REPORT
// ===============================
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


//Sofi
use App\Exports\DataBangunKeluargaExport;
use App\Models\DataBangunKeluarga;

Route::get('/export-bangun-keluarga', function () {
    return DataBangunKeluargaExport::export();
})->name('export.bangunkeluarga');

// ===============================
// MASTER DATA
// ===============================
Route::prefix('master')->name('master.')->group(function () {
    Route::get('/list', fn() => view('master.index'))->name('list');

    Route::get('/{master}', [MasterController::class, 'index'])->name('index');
    Route::get('/{master}/create', [MasterController::class, 'create'])->name('create');
    Route::post('/{master}/store', [MasterController::class, 'store'])->name('store');
    Route::get('/{master}/edit/{id}', [MasterController::class, 'edit'])->name('edit');
    Route::put('/{master}/update/{id}', [MasterController::class, 'update'])->name('update');
    Route::delete('/{master}/destroy/{id}', [MasterController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route untuk menu Administrasi Kependudukan
    Route::get('/kependudukan', [KependudukanController::class, 'index'])->name('menu.kependudukan');

    // Routes untuk Keluarga
    Route::resource('keluarga', KeluargaController::class)->except(['show'])->names('dasar-keluarga');

    //prasarana dasar
    Route::get('prasarana', [PrasaranaDasarController::class, 'index'])->name('keluarga.prasarana.index');
    Route::get('prasarana/create', [PrasaranaDasarController::class, 'create'])->name('keluarga.prasarana.create');
    Route::post('prasarana', [PrasaranaDasarController::class, 'store'])->name('keluarga.prasarana.store');
    Route::get('prasarana/{no_kk}/edit', [PrasaranaDasarController::class, 'edit'])->name('keluarga.prasarana.edit');
    Route::put('prasarana/{no_kk}', [PrasaranaDasarController::class, 'update'])->name('keluarga.prasarana.update');
    Route::delete('prasarana/{no_kk}', [PrasaranaDasarController::class, 'destroy'])->name('keluarga.prasarana.destroy');
    Route::get('prasarana/export/pdf', [PrasaranaDasarController::class, 'exportPdf'])
    ->name('dataprasarana.export.pdf');


    // Routes untuk Aset Keluarga
    Route::get('asetkeluarga', [AsetKeluargaController::class, 'index'])->name('keluarga.asetkeluarga.index');
    Route::get('asetkeluarga/create', [AsetKeluargaController::class, 'create'])->name('keluarga.asetkeluarga.create');
    Route::post('asetkeluarga', [AsetKeluargaController::class, 'store'])->name('keluarga.asetkeluarga.store');
    Route::get('asetkeluarga/{no_kk}/edit', [AsetKeluargaController::class, 'edit'])->name('keluarga.asetkeluarga.edit');
    Route::put('asetkeluarga/{no_kk}', [AsetKeluargaController::class, 'update'])->name('keluarga.asetkeluarga.update');
    Route::delete('asetkeluarga/{no_kk}', [AsetKeluargaController::class, 'destroy'])->name('keluarga.asetkeluarga.destroy');
    Route::get('/laporan/asetkeluarga/pdf', [App\Http\Controllers\AsetKeluargaController::class, 'exportPdf'])->name('asetkeluarga.exportAnalisisPDF');

    // Routes untuk Aset Lahan
    Route::get('asetlahan', [AsetLahanController::class, 'index'])->name('keluarga.asetlahan.index');
    Route::get('asetlahan/create', [AsetLahanController::class, 'create'])->name('keluarga.asetlahan.create');
    Route::post('asetlahan', [AsetLahanController::class, 'store'])->name('keluarga.asetlahan.store');
    Route::get('asetlahan/{no_kk}/edit', [AsetLahanController::class, 'edit'])->name('keluarga.asetlahan.edit');
    Route::put('asetlahan/{no_kk}', [AsetLahanController::class, 'update'])->name('keluarga.asetlahan.update');
    Route::delete('asetlahan/{no_kk}', [AsetLahanController::class, 'destroy'])->name('keluarga.asetlahan.destroy');

    // Routes untuk Aset Perikanan
    Route::get('asetperikanan', [AsetPerikananController::class, 'index'])->name('keluarga.asetperikanan.index');
    Route::get('asetperikanan/create', [AsetPerikananController::class, 'create'])->name('keluarga.asetperikanan.create');
    Route::post('asetperikanan', [AsetPerikananController::class, 'store'])->name('keluarga.asetperikanan.store');
    Route::get('asetperikanan/{no_kk}/edit', [AsetPerikananController::class, 'edit'])->name('keluarga.asetperikanan.edit');
    Route::put('asetperikanan/{no_kk}', [AsetPerikananController::class, 'update'])->name('keluarga.asetperikanan.update');
    Route::delete('asetperikanan/{no_kk}', [AsetPerikananController::class, 'destroy'])->name('keluarga.asetperikanan.destroy');

    // Routes untuk Aset Ternak
    Route::get('asetternak', [AsetTernakController::class, 'index'])->name('keluarga.asetternak.index');
    Route::get('asetternak/create', [AsetTernakController::class, 'create'])->name('keluarga.asetternak.create');
    Route::post('asetternak', [AsetTernakController::class, 'store'])->name('keluarga.asetternak.store');
    Route::get('asetternak/{no_kk}/edit', [AsetTernakController::class, 'edit'])->name('keluarga.asetternak.edit');
    Route::put('asetternak/{no_kk}', [AsetTernakController::class, 'update'])->name('keluarga.asetternak.update');
    Route::delete('asetternak/{no_kk}', [AsetTernakController::class, 'destroy'])->name('keluarga.asetternak.destroy');

    // Routes untuk Sarpras Kerja
    Route::get('sarpraskerja', [SarprasKerjaController::class, 'index'])->name('keluarga.sarpraskerja.index');
    Route::get('sarpraskerja/create', [SarprasKerjaController::class, 'create'])->name('keluarga.sarpraskerja.create');
    Route::post('sarpraskerja', [SarprasKerjaController::class, 'store'])->name('keluarga.sarpraskerja.store');
    Route::get('sarpraskerja/{no_kk}/edit', [SarprasKerjaController::class, 'edit'])->name('keluarga.sarpraskerja.edit');
    Route::put('sarpraskerja/{no_kk}', [SarprasKerjaController::class, 'update'])->name('keluarga.sarpraskerja.update');
    Route::delete('sarpraskerja/{no_kk}', [SarprasKerjaController::class, 'destroy'])->name('keluarga.sarpraskerja.destroy');

    // Routes untuk Sarpras Kerja
    Route::get('bangunkeluarga', [BangunKeluargaController::class, 'index'])->name('keluarga.bangunkeluarga.index');
    Route::get('bangunkeluarga/create', [BangunKeluargaController::class, 'create'])->name('keluarga.bangunkeluarga.create');
    Route::post('bangunkeluarga', [BangunKeluargaController::class, 'store'])->name('keluarga.bangunkeluarga.store');
    Route::get('bangunkeluarga/{no_kk}/edit', [BangunKeluargaController::class, 'edit'])->name('keluarga.bangunkeluarga.edit');
    Route::put('bangunkeluarga/{no_kk}', [BangunKeluargaController::class, 'update'])->name('keluarga.bangunkeluarga.update');
    Route::delete('bangunkeluarga/{no_kk}', [BangunKeluargaController::class, 'destroy'])->name('keluarga.bangunkeluarga.destroy');

    // Routes untuk Sarpras Kerja
    Route::get('sejahterakeluarga', [SejahteraKeluargaController::class, 'index'])->name('keluarga.sejahterakeluarga.index');
    Route::get('sejahterakeluarga/create', [SejahteraKeluargaController::class, 'create'])->name('keluarga.sejahterakeluarga.create');
    Route::post('sejahterakeluarga', [SejahteraKeluargaController::class, 'store'])->name('keluarga.sejahterakeluarga.store');
    Route::get('sejahterakeluarga/{no_kk}/edit', [SejahteraKeluargaController::class, 'edit'])->name('keluarga.sejahterakeluarga.edit');
    Route::put('sejahterakeluarga/{no_kk}', [SejahteraKeluargaController::class, 'update'])->name('keluarga.sejahterakeluarga.update');
    Route::delete('sejahterakeluarga/{no_kk}', [SejahteraKeluargaController::class, 'destroy'])->name('keluarga.sejahterakeluarga.destroy');

    // Routes untuk Sarpras Kerja
    Route::get('konfliksosial', [KonflikSosialController::class, 'index'])->name('keluarga.konfliksosial.index');
    Route::get('konfliksosial/create', [KonflikSosialController::class, 'create'])->name('keluarga.konfliksosial.create');
    Route::post('konfliksosial', [KonflikSosialController::class, 'store'])->name('keluarga.konfliksosial.store');
    Route::get('konfliksosial/{no_kk}/edit', [KonflikSosialController::class, 'edit'])->name('keluarga.konfliksosial.edit');
    Route::put('konfliksosial/{no_kk}', [KonflikSosialController::class, 'update'])->name('keluarga.konfliksosial.update');
    Route::delete('konfliksosial/{no_kk}', [KonflikSosialController::class, 'destroy'])->name('keluarga.konfliksosial.destroy');

    // Routes untuk Sarpras Kerja
    Route::get('kualitasibuhamil', [KualitasIbuHamilController::class, 'index'])->name('keluarga.kualitasibuhamil.index');
    Route::get('kualitasibuhamil/create', [KualitasIbuHamilController::class, 'create'])->name('keluarga.kualitasibuhamil.create');
    Route::post('kualitasibuhamil', [KualitasIbuHamilController::class, 'store'])->name('keluarga.kualitasibuhamil.store');
    Route::get('kualitasibuhamil/{no_kk}/edit', [KualitasIbuHamilController::class, 'edit'])->name('keluarga.kualitasibuhamil.edit');
    Route::put('kualitasibuhamil/{no_kk}', [KualitasIbuHamilController::class, 'update'])->name('keluarga.kualitasibuhamil.update');
    Route::delete('kualitasibuhamil/{no_kk}', [KualitasIbuHamilController::class, 'destroy'])->name('keluarga.kualitasibuhamil.destroy');

    // Routes untuk Sarpras Kerja
    Route::get('kualitasbayi', [KualitasBayiController::class, 'index'])->name('keluarga.kualitasbayi.index');
    Route::get('kualitasbayi/create', [KualitasBayiController::class, 'create'])->name('keluarga.kualitasbayi.create');
    Route::post('kualitasbayi', [KualitasBayiController::class, 'store'])->name('keluarga.kualitasbayi.store');
    Route::get('kualitasbayi/{no_kk}/edit', [KualitasBayiController::class, 'edit'])->name('keluarga.kualitasbayi.edit');
    Route::put('kualitasbayi/{no_kk}', [KualitasBayiController::class, 'update'])->name('keluarga.kualitasbayi.update');
    Route::delete('kualitasbayi/{no_kk}', [KualitasBayiController::class, 'destroy'])->name('keluarga.kualitasbayi.destroy');

    // <<< ===================================================== >>> //

    // Routes untuk Penduduk
    Route::resource('penduduk', PendudukController::class)->except(['show'])->names('dasar-penduduk');

    // Routes untuk kelahiran
    Route::get('kelahiran', [KelahiranController::class, 'index'])->name('penduduk.kelahiran.index');
    Route::get('kelahiran/create', [KelahiranController::class, 'create'])->name('penduduk.kelahiran.create');
    Route::post('kelahiran', [KelahiranController::class, 'store'])->name('penduduk.kelahiran.store');
    Route::get('kelahiran/{nik}/edit', [KelahiranController::class, 'edit'])->name('penduduk.kelahiran.edit');
    Route::put('kelahiran/{nik}', [KelahiranController::class, 'update'])->name('penduduk.kelahiran.update');
    Route::delete('kelahiran/{nik}', [KelahiranController::class, 'destroy'])->name('penduduk.kelahiran.destroy');
    // Route::get('/kelahiran/get-penduduk', [KelahiranController::class, 'getPenduduk'])
    // ->name('kelahiran.get-penduduk');
    // Route::get('/kelahiran/getKabupatens', [KelahiranController::class, 'getKabupatens'])
    // ->name('kelahiran.getKabupatens');
    // Route::get('/kelahiran/getKecamatans', [KelahiranController::class, 'getKecamatans'])
    // ->name('kelahiran.getKecamatans');
    // Route::get('/kelahiran/getDesas', [KelahiranController::class, 'getDesas'])
    // ->name('kelahiran.getDesas');

    // Routes untuk sosial ekonomi
    Route::get('sosialekonomi', [SosialEkonomiController::class, 'index'])->name('penduduk.sosialekonomi.index');
    Route::get('sosialekonomi/create', [SosialEkonomiController::class, 'create'])->name('penduduk.sosialekonomi.create');
    Route::post('sosialekonomi', [SosialEkonomiController::class, 'store'])->name('penduduk.sosialekonomi.store');
    Route::get('sosialekonomi/{nik}/edit', [SosialEkonomiController::class, 'edit'])->name('penduduk.sosialekonomi.edit');
    Route::put('sosialekonomi/{nik}', [SosialEkonomiController::class, 'update'])->name('penduduk.sosialekonomi.update');
    Route::delete('sosialekonomi/{nik}', [SosialEkonomiController::class, 'destroy'])->name('penduduk.sosialekonomi.destroy');
    Route::get('sosialekonomi/report', [SosialEkonomiController::class, 'report'])->name('penduduk.sosialekonomi.report');

    // Routes untuk usahaart
    Route::get('usahaart', [UsahaArtController::class, 'index'])->name('penduduk.usahaart.index');
    Route::get('usahaart/create', [UsahaArtController::class, 'create'])->name('penduduk.usahaart.create');
    Route::post('usahaart', [UsahaArtController::class, 'store'])->name('penduduk.usahaart.store');
    Route::get('usahaart/{nik}/edit', [UsahaArtController::class, 'edit'])->name('penduduk.usahaart.edit');
    Route::put('usahaart/{nik}', [UsahaArtController::class, 'update'])->name('penduduk.usahaart.update');
    Route::delete('usahaart/{nik}', [UsahaArtController::class, 'destroy'])->name('penduduk.usahaart.destroy');
    Route::get('usahaart/report', [UsahaArtController::class, 'report'])->name('penduduk.usahaart.report');

    // Routes untuk programserta
    Route::get('programserta', [ProgramSertaController::class, 'index'])->name('penduduk.programserta.index');
    Route::get('programserta/create', [ProgramSertaController::class, 'create'])->name('penduduk.programserta.create');
    Route::post('programserta', [ProgramSertaController::class, 'store'])->name('penduduk.programserta.store');
    Route::get('programserta/{nik}/edit', [ProgramSertaController::class, 'edit'])->name('penduduk.programserta.edit');
    Route::put('programserta/{nik}', [ProgramSertaController::class, 'update'])->name('penduduk.programserta.update');
    Route::delete('programserta/{nik}', [ProgramSertaController::class, 'destroy'])->name('penduduk.programserta.destroy');
    
    // Routes untuk lembaga desa
    Route::get('lemdes', [LembagaDesaController::class, 'index'])->name('penduduk.lemdes.index');
    Route::get('lemdes/create', [LembagaDesaController::class, 'create'])->name('penduduk.lemdes.create');
    Route::post('lemdes', [LembagaDesaController::class, 'store'])->name('penduduk.lemdes.store');
    Route::get('lemdes/{nik}/edit', [LembagaDesaController::class, 'edit'])->name('penduduk.lemdes.edit');
    Route::put('lemdes/{nik}', [LembagaDesaController::class, 'update'])->name('penduduk.lemdes.update');
    Route::delete('lemdes/{nik}', [LembagaDesaController::class, 'destroy'])->name('penduduk.lemdes.destroy');
    
    // Routes untuk lembaga masyarakat
    Route::get('lemmas', [LembagaMasyarakatController::class, 'index'])->name('penduduk.lembagamasyarakat.index');
    Route::get('lemmas/create', [LembagaMasyarakatController::class, 'create'])->name('penduduk.lembagamasyarakat.create');
    Route::post('lemmas', [LembagaMasyarakatController::class, 'store'])->name('penduduk.lembagamasyarakat.store');
    Route::get('lemmas/{nik}/edit', [LembagaMasyarakatController::class, 'edit'])->name('penduduk.lembagamasyarakat.edit');
    Route::put('lemmas/{nik}', [LembagaMasyarakatController::class, 'update'])->name('penduduk.lembagamasyarakat.update');
    Route::delete('lemmas/{nik}', [LembagaMasyarakatController::class, 'destroy'])->name('penduduk.lembagamasyarakat.destroy');
    
    //Routes untuk lembaga ekonomi
    Route::get('lembagaekonomi', [LembagaEkonomiController::class, 'index'])->name('penduduk.lembagaekonomi.index');
    Route::get('lembagaekonomi/create', [LembagaEkonomiController::class, 'create'])->name('penduduk.lembagaekonomi.create');
    Route::post('lembagaekonomi', [LembagaEkonomiController::class, 'store'])->name('penduduk.lembagaekonomi.store');
    Route::get('lembagaekonomi/{nik}/edit', [LembagaEkonomiController::class, 'edit'])->name('penduduk.lembagaekonomi.edit');
    Route::put('lembagaekonomi/{nik}', [LembagaEkonomiController::class, 'update'])->name('penduduk.lembagaekonomi.update');
    Route::delete('lembagaekonomi/{nik}', [LembagaEkonomiController::class, 'destroy'])->name('penduduk.lembagaekonomi.destroy');

    
    
});

// ===============================
// MENU LAIN (STATIC VIEW)
// ===============================
Route::get('/menu-utama', fn() => view('menu-utama'))->name('menu.utama');
Route::get('/menu-kependudukan', fn() => view('menu-kependudukan'))->name('menu.kependudukan');

// ===============================
// AUTH
// ===============================
require __DIR__.'/auth.php';
