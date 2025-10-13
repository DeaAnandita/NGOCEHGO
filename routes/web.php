<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('master')->name('master.')->group(function () {
    Route::get('/list', function() {
        return view('master.index'); // Halaman master list dengan ikon
    })->name('list');

    Route::get('/{master}', [MasterController::class, 'index'])->name('index');
    Route::get('/{master}/create', [MasterController::class, 'create'])->name('create');
    Route::post('/{master}/store', [MasterController::class, 'store'])->name('store');
    Route::get('/{master}/edit/{id}', [MasterController::class, 'edit'])->name('edit');
    Route::put('/{master}/update/{id}', [MasterController::class, 'update'])->name('update');
    Route::delete('/{master}/destroy/{id}', [MasterController::class, 'destroy'])->name('destroy');
});

// Menu lain
Route::get('/menu-utama', fn() => view('menu-utama'))->name('menu.utama');
Route::get('/menu-kependudukan', fn() => view('menu-kependudukan'))->name('menu.kependudukan');

require __DIR__.'/auth.php';
