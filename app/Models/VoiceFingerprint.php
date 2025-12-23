<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceFingerprint extends Model
{
    protected $table = 'voice_prints';

    protected $fillable = [
        'no_kk',
        'embedding'  // PASTIKAN INI 'embedding'
    ];

    protected $casts = [
        'embedding' => 'array'  // Laravel otomatis decode JSON ke array
    ];

    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_Kk');
    }
}