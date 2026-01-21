<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterMetodeKeputusan extends Model
{
    protected $table = 'master_metode_keputusan';
    protected $primaryKey = 'kdmetode';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdmetode',
        'metode'
    ];
}
