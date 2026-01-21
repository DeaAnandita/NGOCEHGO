<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisKeputusanUmum extends Model
{
    protected $table = 'master_jeniskeputusan_umum';
    protected $primaryKey = 'kdjeniskeputusan_umum';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjeniskeputusan_umum',
        'jeniskeputusan_umum'
    ];
}
