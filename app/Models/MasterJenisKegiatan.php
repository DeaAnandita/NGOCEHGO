<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisKegiatan extends Model
{
    protected $table = 'master_jenis_kegiatan';
    protected $primaryKey = 'kdjenis';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjenis',
        'jenis_kegiatan'
    ];
}
