<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSumberDayaTerpasang extends Model
{
    protected $table = 'master_sumberdayaterpasang';
    protected $primaryKey = 'kdsumberdayaterpasang';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdsumberdayaterpasang', 'sumberdayaterpasang'];
}
