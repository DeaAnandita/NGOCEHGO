<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabTempatPersalinan extends Model
{
    protected $table = 'master_jawabtempatpersalinan';
    protected $primaryKey = 'kdjawabtempatpersalinan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawabtempatpersalinan', 'jawabtempatpersalinan'];
}
