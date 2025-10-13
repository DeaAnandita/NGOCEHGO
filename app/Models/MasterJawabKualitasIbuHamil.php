<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabKualitasIbuHamil extends Model
{
    protected $table = 'master_jawabkualitasibuhamil';
    protected $primaryKey = 'kdjawabkualitasibuhamil';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawabkualitasibuhamil', 'jawabkualitasibuhamil'];
}
