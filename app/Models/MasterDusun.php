<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDusun extends Model
{
    protected $table = 'master_dusun';
    protected $primaryKey = 'kddusun';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kddusun', 'dusun'];
}

