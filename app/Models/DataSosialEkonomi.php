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

        // relasi master
        'kdpartisipasisekolah',
        'kdtingkatsulitdisabilitas',
        'kdstatuskedudukankerja',
        'kdijasahterakhir',
        'kdpenyakitkronis',
        'kdpendapatanperbulan',
        'kdjenisdisabilitas',
        'kdlapanganusaha',
        'kdimunisasi',
    ];

    /** relasi ke tabel penduduk */
    public function penduduk()
    {
        return $this->belongsTo(DataPenduduk::class, 'nik', 'nik');
    }

    /** relasi ke master tabel */
    public function masterPartisipasiSekolah()
    {
        return $this->belongsTo(MasterPartisipasiSekolah::class, 'kdpartisipasisekolah', 'kdpartisipasisekolah');
    }

    public function masterTingkatSulitDisabilitas()
    {
        return $this->belongsTo(MasterTingkatSulitDisabilitas::class, 'kdtingkatsulitdisabilitas', 'kdtingkatsulitdisabilitas');
    }

    public function masterStatusKedudukanKerja()
    {
        return $this->belongsTo(MasterStatusKedudukanKerja::class, 'kdstatuskedudukankerja', 'kdstatuskedudukankerja');
    }

    public function masterIjasahTerakhir()
    {
        return $this->belongsTo(MasterIjasahTerakhir::class, 'kdijasahterakhir', 'kdijasahterakhir');
    }

    public function masterPenyakitKronis()
    {
        return $this->belongsTo(MasterMasterPenyakitKronis::class, 'kdpenyakitkronis', 'kdpenyakitkronis');
    }

    public function masterPendapatanPerbulan()
    {
        return $this->belongsTo(MasterPendapatanPerbulan::class, 'kdpendapatanperbulan', 'kdpendapatanperbulan');
    }

    public function masterJenisDisabilitas()
    {
        return $this->belongsTo(MasterJenisDisabilitas::class, 'kdjenisdisabilitas', 'kdjenisdisabilitas');
    }

    public function masterLapanganUsaha()
    {
        return $this->belongsTo(MasterLapanganUsaha::class, 'kdlapanganusaha', 'kdlapanganusaha');
    }

    public function masterImunisasi()
    {
        return $this->belongsTo(MasterImunisasi::class, 'kdimunisasi', 'kdimunisasi');
    }
}