<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterMutasiKeluar extends Model
{
    protected $table = 'master_mutasikeluar';
    protected $primaryKey = 'kdmutasikeluar';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdmutasikeluar', 'mutasikeluar'];
}
