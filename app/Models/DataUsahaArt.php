<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataUsahaArt extends Model
{
    use HasFactory;

    protected $table = 'data_usahaart';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nik',
        'kdlapanganusaha',
        'kdtempatusaha',
        'kdomsetusaha',
        'usahaart_jumlahpekerja',
        'usahaart_namausaha',
    ];

    /** relasi ke tabel penduduk */
    public function penduduk()
    {
        return $this->belongsTo(DataPenduduk::class, 'nik', 'nik');
    }

    /** relasi ke master tabel */
    public function lapanganusaha()
    {
        return $this->belongsTo(MasterLapanganUsaha::class, 'kdlapanganusaha', 'kdlapanganusaha');
    }

    public function tempatusaha()
    {
        return $this->belongsTo(MasterTempatUsaha::class, 'kdtempatusaha', 'kdtempatusaha');
    }

    public function omsetusaha()
    {
        return $this->belongsTo(MasterOmsetUsaha::class, 'kdomsetusaha', 'kdomsetusaha');
    }
}
