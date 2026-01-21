<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\DataKeluarga;
use App\Models\DataPenduduk;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route untuk statistik realtime dashboard
Route::get('/statistik-desa', function () {
    $keluarga = DataKeluarga::count();
    $penduduk = DataPenduduk::count();

    return response()->json([
        'keluarga' => $keluarga,
        'penduduk' => $penduduk,
    ]);
});
