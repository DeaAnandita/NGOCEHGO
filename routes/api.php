<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Voice\VoiceFingerprintController;

// Tambahkan ini:
Route::post('/check-voice-duplicate', [VoiceFingerprintController::class, 'checkDuplicate']);