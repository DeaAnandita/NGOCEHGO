<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKelahiran extends Model
{
    use HasFactory;

    protected $table = 'data_kelahiran';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nik',
		'kdtempatpersalinan',
        'kdjeniskelahiran',
        'kdpertolonganpersalinan',
        'kelahiran_jamkelahiran',
        'kelahiran_kelahiranke',
        'kelahiran_berat',
        'kelahiran_panjang',
        'kelahiran_nikibu',
        'kelahiran_nikayah',
		'kelahiran_rw',
		'kelahiran_rt',
		'kdprovinsi',
		'kdkabupaten',
		'kdkecamatan',
		'kddesa',
    ];


    /**
     * Relasi ke data penduduk (ibu).
     */
    public function ibu()
    {
        return $this->belongsTo(DataPenduduk::class, 'kelahiran_nikibu', 'nik');
    }

    /**
     * Relasi ke data penduduk (ayah).
     */
    public function ayah()
    {
        return $this->belongsTo(DataPenduduk::class, 'kelahiran_nikayah', 'nik');
    }

    /**
     * Relasi ke master tempat persalinan.
     */
    public function tempatPersalinan()
    {
        return $this->belongsTo(MasterTempatPersalinan::class, 'kdtempatpersalinan', 'kdtempatpersalinan');
    }

    /**
     * Relasi ke master jenis kelahiran.
     */
    public function jenisKelahiran()
    {
        return $this->belongsTo(MasterJenisKelahiran::class, 'kdjeniskelahiran', 'kdjeniskelahiran');
    }

    /**
     * Relasi ke master pertolongan persalinan.
     */
    public function pertolonganPersalinan()
    {
        return $this->belongsTo(MasterPertolonganPersalinan::class, 'kdpertolonganpersalinan', 'kdpertolonganpersalinan');
    }
}