<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusAnggaran extends Model
{
    protected $table = 'master_status_anggaran';
    protected $primaryKey = 'kdstatus';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdstatus',
        'status_anggaran',
    ];
}
