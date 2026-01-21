<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusHakTanah extends Model
{
    protected $table = 'master_statushak_tanah';
    protected $primaryKey = 'kdstatushaktanah';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdstatushaktanah',
        'statushaktanah'
    ];
}
