<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabSarpras extends Model
{
    protected $table = 'master_jawabsarpras';
    protected $primaryKey = 'kdjawabsarpras';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawabsarpras', 'jawabsarpras'];
}
