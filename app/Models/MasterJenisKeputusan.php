<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisKeputusan extends Model
{
    protected $table = 'master_jenis_keputusan';
    protected $primaryKey = 'kdjenis';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjenis',
        'jenis_keputusan'
    ];
}
