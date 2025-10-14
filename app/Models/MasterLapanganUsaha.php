<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLapanganUsaha extends Model
{
    protected $table = 'master_lapanganusaha';
    protected $primaryKey = 'kdlapanganusaha';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdlapanganusaha', 'lapanganusaha'];
}
