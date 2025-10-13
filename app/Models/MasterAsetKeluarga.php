<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAsetKeluarga extends Model {
    protected $table = 'master_asetkeluarga';
    protected $primaryKey = 'kdasetkeluarga';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdasetkeluarga', 'asetkeluarga'];
}
