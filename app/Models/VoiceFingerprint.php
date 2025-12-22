<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceFingerprint extends Model
{
    protected $table = 'voice_fingerprints';

    protected $fillable = ['keluarga_id', 'fingerprint'];

    // Otomatis cast JSON ke array
    protected $casts = [
        'fingerprint' => 'array'
    ];

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class); // sesuaikan nama model keluarga Anda
    }
}