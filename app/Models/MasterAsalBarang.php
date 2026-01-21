<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAsalBarang extends Model
{
    protected $table = 'master_asalbarang';
    protected $primaryKey = 'kdasalbarang';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdasalbarang',
        'asalbarang'
    ];
}
