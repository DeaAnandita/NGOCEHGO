<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusKedudukanKerja extends Model
{
    protected $table = 'master_statuskedudukankerja';
    protected $primaryKey = 'kdstatuskedudukankerja';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdstatuskedudukankerja', 'statuskedudukankerja'];
}
