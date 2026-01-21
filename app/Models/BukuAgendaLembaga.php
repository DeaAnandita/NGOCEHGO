<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuAgendaLembaga extends Model
{
    protected $table = 'buku_agendalembaga';
    protected $primaryKey = 'kdagendalembaga';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kdagendalembaga',
        'kdjenisagenda_umum',
        'agendalembaga_tanggal',
        'agendalembaga_nomorsurat',
        'agendalembaga_tanggalsurat',
        'agendalembaga_identitassurat',
        'agendalembaga_isisurat',
        'agendalembaga_keterangan',
        'agendalembaga_file',
        'userinput',
        'inputtime'
    ];

    public function jenisAgenda()
    {
        return $this->belongsTo(
            MasterJenisAgendaUmum::class,
            'kdjenisagenda_umum',
            'kdjenisagenda_umum'
        );
    }
}
