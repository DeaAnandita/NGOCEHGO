<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabLemek extends Model
{
    protected $table = 'master_jawablemek';
    protected $primaryKey = 'kdjawablemek';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawablemek', 'jawablemek'];
}
