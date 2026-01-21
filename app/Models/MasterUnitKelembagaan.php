<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterUnitKelembagaan extends Model
{
    protected $table = 'master_unit_kelembagaan';
    protected $primaryKey = 'kdunit';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdunit',
        'nama_unit'
    ];
}
