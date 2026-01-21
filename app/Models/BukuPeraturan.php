<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuPeraturan extends Model
{
    protected $table = 'buku_peraturans';
    protected $primaryKey = 'kdperaturan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kdperaturan',
        'kdjenisperaturandesa',
        'nomorperaturan',
        'judulpengaturan',
        'uraianperaturan',
        'kesepakatanperaturan',
        'keteranganperaturan',
        'filepengaturan',
        'userinput',
        'inputtime',
    ];

    public function jenisPeraturanDesa()
    {
        return $this->belongsTo(
            MasterJenisPeraturanDesa::class,
            'kdjenisperaturandesa',
            'kdjenisperaturandesa'
        );
    }
    
}
