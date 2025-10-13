<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSumberAirMinum extends Model
{
    protected $table = 'master_sumberairminum';
    protected $primaryKey = 'kdsumberairminum';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdsumberairminum', 'sumberairminum'];
}

