<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuKader extends Model
{
    protected $table = 'buku_kader';
    protected $primaryKey = 'reg';
    public $timestamps = false;

    protected $fillable = [
        'kdkader',
        'kader_tanggal',
        'kdpenduduk',
        'kdpendidikan',
        'kdbidang',
        'kader_keterangan',
        'kdstatuskader',
        'userinput',
        'inputtime'
    ];

    public function pendidikan()
    {
        return $this->belongsTo(MasterPendidikan::class, 'kdpendidikan');
    }
    public function bidang()
    {
        return $this->belongsTo(MasterKaderBidang::class, 'kdbidang');
    }
    public function status()
    {
        return $this->belongsTo(MasterStatusKader::class, 'kdstatuskader');
    }
    public function penduduk()
    {
        return $this->belongsTo(DataPenduduk::class, 'kdpenduduk', 'nik');
    }
}
