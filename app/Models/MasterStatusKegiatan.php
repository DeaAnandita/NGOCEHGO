<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusKegiatan extends Model
{
    protected $table = 'master_status_kegiatan';
    protected $primaryKey = 'kdstatus';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdstatus',
        'status_kegiatan'
    ];
}
