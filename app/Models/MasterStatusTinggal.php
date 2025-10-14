<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusTinggal extends Model
{
    protected $table = 'master_statustinggal';
    protected $primaryKey = 'kdstatustinggal';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdstatustinggal', 'statustinggal'];
}