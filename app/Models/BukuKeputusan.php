<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuKeputusan extends Model
{
    protected $table = 'buku_keputusan';
    protected $primaryKey = 'kd_keputusan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kd_keputusan',
        'nomor_keputusan',
        'tanggal_keputusan',
        'judul_keputusan',
        'kdjeniskeputusan_umum',
        'uraian_keputusan',
        'keterangan_keputusan',
        'file_keputusan',
        'userinput',
        'inputtime'
    ];

    public function jenisKeputusan()
    {
        return $this->belongsTo(
            MasterJenisKeputusanUmum::class,
            'kdjeniskeputusan_umum',
            'kdjeniskeputusan_umum'
        );
    }
}
