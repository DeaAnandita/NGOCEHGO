<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabKualitasBayi extends Model
{
    protected $table = 'master_jawabkualitasbayi';
    protected $primaryKey = 'kdjawabkualitasbayi';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawabkualitasbayi', 'jawabkualitasbayi'];
}
