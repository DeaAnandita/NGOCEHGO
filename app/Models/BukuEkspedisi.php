<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuEkspedisi extends Model
{
    protected $table = 'buku_ekspedisi';
    protected $primaryKey = 'kdekspedisi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kdekspedisi',
        'ekspedisi_tanggal',
        'ekspedisi_tanggalsurat',
        'ekspedisi_nomorsurat',
        'ekspedisi_identitassurat',
        'ekspedisi_isisurat',
        'ekspedisi_keterangan',
        'ekspedisi_file',
        'userinput',
        'inputtime'
    ];
}
