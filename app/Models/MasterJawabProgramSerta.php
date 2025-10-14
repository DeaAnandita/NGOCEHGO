<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabProgramSerta extends Model
{
    protected $table = 'master_jawabprogramserta';
    protected $primaryKey = 'kdjawabprogramserta';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawabprogramserta', 'jawabprogramserta'];
}

