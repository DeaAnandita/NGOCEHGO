<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJabatanKelembagaan extends Model
{
    protected $table = 'master_jabatan_kelembagaan';
    protected $primaryKey = 'kdjabatan';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjabatan',
        'jabatan',
    ];
}
