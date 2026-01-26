<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSosialEkonomi extends Model
{
    use HasFactory;

    protected $table = 'data_sosialekonomi';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nik',
        'kdpartisipasisekolah',
        'kdijasahterakhir',
        'kdjenisdisabilitas',
        'kdtingkatsulitdisabilitas',
        'kdpenyakitkronis',
        'kdlapanganusaha',
        'kdstatuskedudukankerja',
        'kdpendapatanperbulan',
        'kdimunisasi',
    ];

    // Relasi ke tabel penduduk
    public function penduduk()
    {
        return $this->belongsTo(DataPenduduk::class, 'nik', 'nik');
    }

    // Relasi ke tabel master
    public function partisipasisekolah()
    {
        return $this->belongsTo(MasterPartisipasiSekolah::class, 'kdpartisipasisekolah', 'kdpartisipasisekolah');
    }

    public function ijasahterakhir()
    {
        return $this->belongsTo(MasterIjasahTerakhir::class, 'kdijasahterakhir', 'kdijasahterakhir');
    }

    public function jenisdisabilitas()
    {
        return $this->belongsTo(MasterJenisDisabilitas::class, 'kdjenisdisabilitas', 'kdjenisdisabilitas');
    }

    public function tingkatsulitdisabilitas()
    {
        return $this->belongsTo(MasterTingkatSulitDisabilitas::class, 'kdtingkatsulitdisabilitas', 'kdtingkatsulitdisabilitas');
    }

    public function penyakitkronis()
    {
        return $this->belongsTo(MasterPenyakitKronis::class, 'kdpenyakitkronis', 'kdpenyakitkronis');
    }

    public function lapanganusaha()
    {
        return $this->belongsTo(MasterLapanganUsaha::class, 'kdlapanganusaha', 'kdlapanganusaha');
    }

    public function statuskedudukankerja()
    {
        return $this->belongsTo(MasterStatusKedudukanKerja::class, 'kdstatuskedudukankerja', 'kdstatuskedudukankerja');
    }

    public function pendapatanperbulan()
    {
        return $this->belongsTo(MasterPendapatanPerbulan::class, 'kdpendapatanperbulan', 'kdpendapatanperbulan');
    }

    public function imunisasi()
    {
        return $this->belongsTo(MasterImunisasi::class, 'kdimunisasi', 'kdimunisasi');
    }
}
