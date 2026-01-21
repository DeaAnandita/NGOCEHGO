<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPeriodeKelembagaan extends Model
{
    protected $table = 'master_periode_kelembagaan';
    protected $primaryKey = 'kdperiode';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdperiode',
        'tahun_awal',

    ];
}
