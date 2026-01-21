<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterMutasiTanah extends Model
{
    protected $table = 'master_mutasi_tanah';
    protected $primaryKey = 'kdmutasitanah';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdmutasitanah',
        'mutasitanah'
    ];
}
