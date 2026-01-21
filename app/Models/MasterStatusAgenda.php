<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusAgenda extends Model
{
    protected $table = 'master_status_agenda';
    protected $primaryKey = 'kdstatus';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdstatus',
        'status_agenda'
    ];
}
