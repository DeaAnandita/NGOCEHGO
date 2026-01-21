<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusKader extends Model
{
    protected $table = 'master_status_kader';
    protected $primaryKey = 'kdstatuskader';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['kdstatuskader', 'statuskader'];
}
