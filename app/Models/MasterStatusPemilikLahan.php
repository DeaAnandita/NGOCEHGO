<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusPemilikLahan extends Model
{
    protected $table = 'master_statuspemiliklahan';
    protected $primaryKey = 'kdstatuspemiliklahan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdstatuspemiliklahan', 'statuspemiliklahan'];
}
