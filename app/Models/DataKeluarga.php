<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKeluarga extends Model
{
    use HasFactory;

    protected $table = 'data_keluarga';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kdmutasimasuk',
        'keluarga_tanggalmutasi',
        'no_kk',
        'keluarga_kepalakeluarga',
        'kddusun',
        'keluarga_rw',
        'keluarga_rt',
        'keluarga_alamatlengkap',
        'kdprovinsi',
        'kdkabupaten',
        'kdkecamatan',
        'kddesa',
    ];

    /**
     * Relasi ke data_penduduk
     * Satu keluarga bisa punya banyak penduduk
     */
    public function penduduk()
    {
        return $this->hasMany(DataPenduduk::class, 'no_kk', 'no_kk');
    }
    public function asetkeluarga()
    {
        return $this->hasMany(DataAsetKeluarga::class, 'no_kk', 'no_kk');
    }
    public function asetlahan()
    {
        return $this->hasMany(DataAsetLahan::class, 'no_kk', 'no_kk');
    }
    public function prasdas()
    {
        return $this->hasMany(DataPrasaranaDasar::class, 'no_kk', 'no_kk');
    }
    public function asetternak()
    {
        return $this->hasMany(DataAsetTernak::class, 'no_kk', 'no_kk');
    }
    public function asetperikanan()
    {
        return $this->hasMany(DataAsetPerikanan::class, 'no_kk', 'no_kk');
    }
    public function sarpraskerja()
    {
        return $this->hasMany(DataSarprasKerja::class, 'no_kk', 'no_kk');
    }
    public function bangunkeluarga()
    {
        return $this->hasMany(DataBangunKeluarga::class, 'no_kk', 'no_kk');
    }
    public function sejahterakeluarga()
    {
        return $this->hasMany(DataSejahteraKeluarga::class, 'no_kk', 'no_kk');
    }
    public function konfliksosial()
    {
        return $this->hasMany(DataKonflikSosial::class, 'no_kk', 'no_kk');
    }
    public function kualitasibuhamil()
    {
        return $this->hasMany(DataKualitasIbuHamil::class, 'no_kk', 'no_kk');
    }
    public function kualitasbayi()
    {
        return $this->hasMany(DataKualitasBayi::class, 'no_kk', 'no_kk');
    }
    

    /**
     * Relasi ke master tabel
     */
    public function mutasi()
    {
        return $this->belongsTo(MasterMutasiMasuk::class, 'kdmutasimasuk', 'kdmutasimasuk');
    }

    public function dusun()
    {
        return $this->belongsTo(MasterDusun::class, 'kddusun', 'kddusun');
    }

    // Wilayah mutasi datang (nullable)
    public function provinsi()
    {
        return $this->belongsTo(MasterProvinsi::class, 'kdprovinsi', 'kdprovinsi');
    }

    public function kabupaten()
    {
        return $this->belongsTo(MasterKabupaten::class, 'kdkabupaten', 'kdkabupaten');
    }

    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'kdkecamatan', 'kdkecamatan');
    }

    public function desa()
    {
        return $this->belongsTo(MasterDesa::class, 'kddesa', 'kddesa');
    }
}
