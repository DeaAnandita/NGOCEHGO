<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterMutasiMasuk extends Model
{
    protected $table = 'master_mutasimasuk';
    protected $primaryKey = 'kdmutasimasuk';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdmutasimasuk', 'mutasimasuk'];
}
