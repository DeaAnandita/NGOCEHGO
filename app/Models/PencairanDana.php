<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PencairanDana extends Model
{
    protected $table = 'pencairan_dana';
    protected $fillable = [
        'kegiatan_id',
        'tanggal_cair',
        'jumlah',
        'no_sp2d',
        'bukti_transfer',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function realisasi()
    {
        return $this->hasMany(RealisasiPengeluaran::class, 'pencairan_id');
    }
    public function realisasiPengeluaran()
    {
        return $this->hasMany(RealisasiPengeluaran::class, 'pencairan_id');
    }
}
