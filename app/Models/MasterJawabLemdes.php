<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabLemdes extends Model
{
    protected $table = 'master_jawablemdes';
    protected $primaryKey = 'kdjawablemdes';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawablemdes', 'jawablemdes'];
}
