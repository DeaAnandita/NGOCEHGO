<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusLpj extends Model
{
    protected $table = 'master_status_lpj';
    protected $primaryKey = 'kdstatus';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdstatus',
        'status_lpj',
    ];
}
