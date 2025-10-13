<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKualitasBayi extends Model
{
    protected $table = 'master_kualitasbayi';
    protected $primaryKey = 'kdkualitasbayi';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkualitasbayi', 'kualitasbayi'];
}
