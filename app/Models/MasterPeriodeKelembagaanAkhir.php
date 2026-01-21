<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPeriodeKelembagaanAkhir extends Model
{
    protected $table = 'master_periode_kelembagaan_akhir';
    protected $primaryKey = 'kdperiode';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kdperiode',
        'akhir'
    ];

    // Relasi balik ke awal
    public function periode()
    {
        return $this->belongsTo(
            MasterPeriodeKelembagaan::class,
            'kdperiode',
            'kdperiode'
        );
    }
}
