<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAktaNikah extends Model
{
    protected $table = 'master_aktanikah';
    protected $primaryKey = 'kdaktanikah';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdaktanikah', 'aktanikah'];
}
