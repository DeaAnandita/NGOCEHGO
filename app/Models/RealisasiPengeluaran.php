<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiPengeluaran extends Model
{
    protected $table = 'realisasi_pengeluaran';
    protected $fillable = [
        'pencairan_id',
        'tanggal',
        'uraian',
        'jumlah',
        'bukti',
    ];

    public function pencairan()
    {
        return $this->belongsTo(PencairanDana::class, 'pencairan_id');
    }
}
