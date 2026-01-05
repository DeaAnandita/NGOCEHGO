<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceFingerprint extends Model
{
    protected $table = 'voice_prints';

    /**
     * Kolom yang dapat diisi secara massal
     */
    protected $fillable = [
        'no_kk',      // Nomor Kartu Keluarga (opsional, untuk grouping)
        'nik',        // NIK penduduk - primary identifier untuk voice print
        'embedding',  // Vector embedding suara (disimpan sebagai JSON)
    ];

    /**
     * Cast attribute agar embedding otomatis dikonversi menjadi array
     */
    protected $casts = [
        'embedding' => 'array',  // Laravel akan json_decode otomatis saat diakses
    ];

    /**
     * Relasi ke tabel data_keluarga berdasarkan no_kk
     */
    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_kk', 'no_kk');
    }

    /**
     * Relasi ke tabel data_penduduk berdasarkan nik
     * (pastikan model DataPenduduk sudah ada)
     */
    public function penduduk()
    {
        return $this->belongsTo(DataPenduduk::class, 'nik', 'nik');
    }

    /**
     * Scope untuk mencari berdasarkan NIK
     */
    public function scopeByNik($query, $nik)
    {
        return $query->where('nik', $nik);
    }

    /**
     * Scope untuk mencari berdasarkan No KK
     */
    public function scopeByNoKk($query, $noKk)
    {
        return $query->where('no_kk', $noKk);
    }
}