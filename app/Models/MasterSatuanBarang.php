<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSatuanBarang extends Model
{
    protected $table = 'master_satuanbarang';
    protected $primaryKey = 'kdsatuanbarang';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdsatuanbarang',
        'satuanbarang'
    ];
}
