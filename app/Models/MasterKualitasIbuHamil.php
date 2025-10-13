<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKualitasIbuHamil extends Model
{
    protected $table = 'master_kualitasibuhamil';
    protected $primaryKey = 'kdkualitasibuhamil';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkualitasibuhamil', 'kualitasibuhamil'];
}
