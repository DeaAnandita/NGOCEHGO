<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusPencairan extends Model
{
    protected $table = 'master_status_pencairan';
    protected $primaryKey = 'kdstatus';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdstatus',
        'status_pencairan',
    ];
}
