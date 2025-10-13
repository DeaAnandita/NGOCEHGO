<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAsetTernak extends Model
{
    protected $table = 'master_asetternak';
    protected $primaryKey = 'kdasetternak';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdasetternak', 'asetternak'];
}
