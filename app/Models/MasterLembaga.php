<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLembaga extends Model
{
    protected $table = 'master_lembaga';
    protected $primaryKey = 'kdlembaga';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdlembaga', 'kdjenislembaga', 'lembaga' ];

    public function jenislembaga() {
        return $this->belongsTo(MasterJenisLembaga::class, 'kdjenislembaga', 'kdjenislembaga');
    }
}
