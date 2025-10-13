<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisDindingBangunan extends Model
{
    protected $table = 'master_jenisdindingbangunan';
    protected $primaryKey = 'kdjenisdindingbangunan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjenisdindingbangunan', 'jenisdindingbangunan'];
}
