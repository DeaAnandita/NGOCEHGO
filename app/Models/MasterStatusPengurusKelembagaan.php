<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusPengurusKelembagaan extends Model
{
    protected $table = 'master_status_pengurus_kelembagaan';
    protected $primaryKey = 'kdstatus';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdstatus',
        'status_pengurus'
    ];
}
