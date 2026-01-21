<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisAgenda extends Model
{
    protected $table = 'master_jenis_agenda';
    protected $primaryKey = 'kdjenis';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjenis',
        'jenis_agenda'
    ];
}
