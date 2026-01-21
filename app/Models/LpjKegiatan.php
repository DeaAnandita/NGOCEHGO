<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpjKegiatan extends Model
{
    protected $table = 'lpj_kegiatan';
    protected $fillable = [
        'kegiatan_id',
        'total_anggaran',
        'total_realisasi',
        'sisa_anggaran',
        'file_lpj',
        'status',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}
