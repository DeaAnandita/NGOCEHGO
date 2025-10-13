<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisAtapBangunan extends Model
{
    protected $table = 'master_jenisatapbangunan';
    protected $primaryKey = 'kdjenisatapbangunan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjenisatapbangunan', 'jenisatapbangunan'];
}
