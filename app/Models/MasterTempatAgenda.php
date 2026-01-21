<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTempatAgenda extends Model
{
    protected $table = 'master_tempat_agenda';
    protected $primaryKey = 'kdtempat';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdtempat',
        'tempat_agenda'
    ];
}
