<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusPemilikBangunan extends Model
{
    protected $table = 'master_statuspemilikbangunan';
    protected $primaryKey = 'kdstatuspemilikbangunan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdstatuspemilikbangunan', 'statuspemilikbangunan'];
}
