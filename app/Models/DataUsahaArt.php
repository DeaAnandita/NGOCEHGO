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

        // relasi master
        'kdlapanganusaha',
        'kdtempatusaha',
        'kdomsetusaha',

        // field isian langsung
        'usahaart_jumlahpekerja',
        'usahaart_namausaha',
    ];

    /** relasi ke tabel penduduk */
    public function penduduk()
    {
        return $this->belongsTo(DataPenduduk::class, 'nik', 'nik');
    }

    /** relasi ke master tabel */
    public function masterLapanganUsaha()
    {
        return $this->belongsTo(MasterLapanganUsaha::class, 'kdlapanganusaha', 'kdlapanganusaha');
    }

    public function masterTempatUsaha()
    {
        return $this->belongsTo(MasterTempatUsaha::class, 'kdtempatusaha', 'kdtempatusaha');
    }

    public function masterOmsetUsaha()
    {
        return $this->belongsTo(MasterOmsetUsaha::class, 'kdomsetusaha', 'kdomsetusaha');
    }
}