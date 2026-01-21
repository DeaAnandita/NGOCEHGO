<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisAgendaUmum extends Model
{
    protected $table = 'master_jenisagenda_umum';
    protected $primaryKey = 'kdjenisagenda_umum';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjenisagenda_umum',
        'jenisagenda_umum'
    ];
}
