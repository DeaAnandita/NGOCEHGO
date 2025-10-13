<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisLantaiBangunan extends Model
{
    protected $table = 'master_jenislantaibangunan';
    protected $primaryKey = 'kdjenislantaibangunan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjenislantaibangunan', 'jenislantaibangunan'];
}
