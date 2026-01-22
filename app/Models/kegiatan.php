<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'keputusan_id',
        'kdjenis',
        'kdunit',
        'kdperiode',
        'kdstatus',
        'kdsumber',
        'nama_kegiatan',
        'lokasi',
        'pagu_anggaran',
        'tgl_mulai',
        'tgl_selesai',
    ];

    // =========================
    // RELATIONSHIP
    // =========================

    public function keputusan()
    {
        return $this->belongsTo(Keputusan::class, 'keputusan_id', 'id');
    }

    public function jenis()
    {
        return $this->belongsTo(MasterJenisKegiatan::class, 'kdjenis', 'kdjenis');
    }

    public function unit()
    {
        return $this->belongsTo(MasterUnitKeputusan::class, 'kdunit', 'kdunit');
    }

    public function status()
    {
        return $this->belongsTo(MasterStatusKegiatan::class, 'kdstatus', 'kdstatus');
    }

    public function sumberDana()
    {
        return $this->belongsTo(MasterSumberDana::class, 'kdsumber', 'kdsumber');
    }

    public function periode()
    {
        return $this->belongsTo(MasterPeriodeKelembagaan::class, 'kdperiode', 'kdperiode');
    }

    public function pencairanDana()
    {
        return $this->hasMany(PencairanDana::class, 'kegiatan_id', 'id');
    }
}
