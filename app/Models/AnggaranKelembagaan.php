<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggaranKelembagaan extends Model
{
    protected $table = 'anggaran_kelembagaan';

    protected $fillable = [
        'kdunit',
        'kdperiode',
        'kdsumber',
        'total_anggaran',
        'keterangan'
    ];

    public function unit()
    {
        return $this->belongsTo(MasterUnitKeputusan::class, 'kdunit', 'kdunit');
    }

    public function periode()
    {
        return $this->belongsTo(MasterPeriodeKelembagaan::class, 'kdperiode', 'kdperiode');
    }

    // HANYA SATU RELASI
    public function kegiatanAnggaran()
    {
        return $this->hasMany(KegiatanAnggaran::class, 'anggaran_id');
    }

    public function sumber()
    {
        return $this->belongsTo(MasterSumberDana::class, 'kdsumber');
    }
}
