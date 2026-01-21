<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaKelembagaan extends Model
{
    protected $table = 'agenda_kelembagaan';

    protected $fillable = [
        'kdjenis',
        'kdunit',
        'kdstatus',
        'kdtempat',
        'kdperiode',
        'judul_agenda',
        'uraian_agenda',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    // =========================
    // RELATIONSHIP
    // =========================

    public function jenis()
    {
        return $this->belongsTo(\App\Models\MasterJenisAgenda::class, 'kdjenis');
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\MasterUnitKeputusan::class, 'kdunit');
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\MasterStatusAgenda::class, 'kdstatus');
    }

    public function tempat()
    {
        return $this->belongsTo(\App\Models\MasterTempatAgenda::class, 'kdtempat');
    }

    public function periode()
    {
        return $this->belongsTo(\App\Models\MasterPeriodeKelembagaan::class, 'kdperiode');
    }
}
