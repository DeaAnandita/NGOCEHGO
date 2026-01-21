<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keputusan extends Model
{
    protected $table = 'keputusan';

    protected $fillable = [
        'kdjenis',
        'kdunit',
        'kdperiode',
        'kdstatus',
        'kdmetode',
        'nomor_sk',
        'judul_keputusan',
        'tanggal_keputusan',
        'kdjabatan',
    ];
    public function jenis()
    {
        return $this->belongsTo(MasterJenisKeputusan::class, 'kdjenis', 'kdjenis');
    }

    public function unit()
    {
        return $this->belongsTo(MasterUnitKeputusan::class, 'kdunit', 'kdunit');
    }

    public function periode()
    {
        return $this->belongsTo(MasterPeriodeKelembagaan::class, 'kdperiode', 'kdperiode');
    }

    public function jabatan()
    {
        return $this->belongsTo(MasterJabatanKelembagaan::class, 'kdjabatan', 'kdjabatan');
    }

    public function status()
    {
        return $this->belongsTo(MasterStatusKeputusan::class, 'kdstatus', 'kdstatus');
    }

    public function metode()
    {
        return $this->belongsTo(MasterMetodeKeputusan::class, 'kdmetode', 'kdmetode');
    }
}
