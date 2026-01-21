<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPengguna extends Model
{
    protected $table = 'master_pengguna';
    protected $primaryKey = 'kdpengguna';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdpengguna',
        'kodepengguna',
        'pengguna'
    ];
}
