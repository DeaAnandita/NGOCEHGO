<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisDisabilitas extends Model
{
    protected $table = 'master_jenisdisabilitas';
    protected $primaryKey = 'kdjenisdisabilitas';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjenisdisabilitas', 'jenisdisabilitas'];
}
