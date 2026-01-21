<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisPeraturanDesa extends Model
{
    protected $table = 'master_jenisperaturandesa';
    protected $primaryKey = 'kdjenisperaturandesa';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjenisperaturandesa',
        'jenisperaturandesa'
    ];
}
