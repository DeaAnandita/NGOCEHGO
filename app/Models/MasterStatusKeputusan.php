<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusKeputusan extends Model
{
    protected $table = 'master_status_keputusan';
    protected $primaryKey = 'kdstatus';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdstatus',
        'status_keputusan'
    ];
}
