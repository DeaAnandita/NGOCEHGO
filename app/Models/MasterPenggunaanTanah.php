<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPenggunaanTanah extends Model
{
    protected $table = 'master_penggunaan_tanah';
    protected $primaryKey = 'kdpenggunaantanah';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdpenggunaantanah',
        'penggunaantanah'
    ];
}
