<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKondisiLapanganUsaha extends Model
{
    protected $table = 'master_kondisilapanganusaha';
    protected $primaryKey = 'kdkondisilapanganusaha';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkondisilapanganusaha', 'kondisilapanganusaha'];
}
