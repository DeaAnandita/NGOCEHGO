<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanAnggaran extends Model
{
    protected $table = 'kegiatan_anggaran';

    protected $fillable = [
        'anggaran_id',
        'kegiatan_id',
        'kdsumber',
        'nilai_anggaran'
    ];

    public function anggaran()
    {
        return $this->belongsTo(AnggaranKelembagaan::class, 'anggaran_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(kegiatan::class, 'kegiatan_id');
    }

    // HANYA SATU RELASI
    public function sumber()
    {
        return $this->belongsTo(MasterSumberDana::class, 'kdsumber', 'kdsumber');
    }
}
