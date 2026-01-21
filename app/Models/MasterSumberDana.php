<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSumberDana extends Model
{
    protected $table = 'master_sumber_dana';
    protected $primaryKey = 'kdsumber';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdsumber',
        'sumber_dana'
    ];
}
