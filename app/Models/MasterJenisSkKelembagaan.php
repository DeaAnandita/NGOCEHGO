<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisSkKelembagaan extends Model
{
    protected $table = 'master_jenis_sk_kelembagaan';
    protected $primaryKey = 'kdjenissk';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjenissk',
        'jenis_sk'
    ];
}
