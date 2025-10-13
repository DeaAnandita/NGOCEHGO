<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisLembaga extends Model
{
    protected $table = 'master_jenislembaga';
    protected $primaryKey = 'kdjenislembaga';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjenislembaga', 'jenislembaga'];

    public function lembaga() {
        return $this->hasMany(MasterLembaga::class, 'kdjenislembaga', 'kdjenislembaga');
    }
}
