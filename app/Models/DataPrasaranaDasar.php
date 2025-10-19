<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPrasaranaDasar extends Model
{
    use HasFactory;

    protected $table = 'data_prasaranadasar';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'no_kk',
        'kdstatuspemilikbangunan', 'kdstatuspemiliklahan', 'kdjenisfisikbangunan',
        'kdjenislantaibangunan', 'kdkondisilantaibangunan', 'kdjenisdindingbangunan', 'kdkondisidindingbangunan',
        'kdjenisatapbangunan', 'kdkondisiatapbangunan', 'kdsumberairminum', 'kdkondisisumberair', 'kdcaraperolehanair',
        'kdsumberpeneranganutama', 'kdsumberdayaterpasang', 'kdbahanbakarmemasak', 'kdfasilitastempatbab',
        'kdpembuanganakhirtinja', 'kdcarapembuangansampah', 'kdmanfaatmataair',
        'prasdas_luaslantai', 'prasdas_jumlahkamar'
    ];

    // Relationships
    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_kk', 'no_kk');
    }

    public function statuspemilikbangunan()
    {
        return $this->belongsTo(MasterStatusPemilikBangunan::class, 'kdstatuspemilikbangunan', 'kdstatuspemilikbangunan');
    }

    public function statuspemiliklahan()
    {
        return $this->belongsTo(MasterStatusPemilikLahan::class, 'kdstatuspemiliklahan', 'kdstatuspemiliklahan');
    }

    public function jenisfisikbangunan()
    {
        return $this->belongsTo(MasterJenisFisikBangunan::class, 'kdjenisfisikbangunan', 'kdjenisfisikbangunan');
    }

    public function jenislantaibangunan()
    {
        return $this->belongsTo(MasterJenisLantaiBangunan::class, 'kdjenislantaibangunan', 'kdjenislantaibangunan');
    }

    public function kondisilantaibangunan()
    {
        return $this->belongsTo(MasterKondisiLantaiBangunan::class, 'kdkondisilantaibangunan', 'kdkondisilantaibangunan');
    }

    public function jenisdindingbangunan()
    {
        return $this->belongsTo(MasterJenisDindingBangunan::class, 'kdjenisdindingbangunan', 'kdjenisdindingbangunan');
    }

    public function kondisidindingbangunan()
    {
        return $this->belongsTo(MasterKondisiDindingBangunan::class, 'kdkondisidindingbangunan', 'kdkondisidindingbangunan');
    }

    public function jenisatapbangunan()
    {
        return $this->belongsTo(MasterJenisAtapBangunan::class, 'kdjenisatapbangunan', 'kdjenisatapbangunan');
    }

    public function kondisiatapbangunan()
    {
        return $this->belongsTo(MasterKondisiAtapBangunan::class, 'kdkondisiatapbangunan', 'kdkondisiatapbangunan');
    }

    public function sumberairminum()
    {
        return $this->belongsTo(MasterSumberAirMinum::class, 'kdsumberairminum', 'kdsumberairminum');
    }

    public function kondisisumberair()
    {
        return $this->belongsTo(MasterKondisiSumberAir::class, 'kdkondisisumberair', 'kdkondisisumberair');
    }

    public function caraperolehanair()
    {
        return $this->belongsTo(MasterCaraPerolehanAir::class, 'kdcaraperolehanair', 'kdcaraperolehanair');
    }

    public function sumberpeneranganutama()
    {
        return $this->belongsTo(MasterSumberPeneranganUtama::class, 'kdsumberpeneranganutama', 'kdsumberpeneranganutama');
    }

    public function sumberdayaterpasang()
    {
        return $this->belongsTo(MasterSumberDayaTerpasang::class, 'kdsumberdayaterpasang', 'kdsumberdayaterpasang');
    }

    public function bahanbakarmemasak()
    {
        return $this->belongsTo(MasterBahanBakarMemasak::class, 'kdbahanbakarmemasak', 'kdbahanbakarmemasak');
    }

    public function fasilitastempatbab()
    {
        return $this->belongsTo(MasterFasilitasTempatBab::class, 'kdfasilitastempatbab', 'kdfasilitastempatbab');
    }

    public function pembuanganakhirtinja()
    {
        return $this->belongsTo(MasterPembuanganAkhirTinja::class, 'kdpembuanganakhirtinja', 'kdpembuanganakhirtinja');
    }

    public function carapembuangansampah()
    {
        return $this->belongsTo(MasterCaraPembuanganSampah::class, 'kdcarapembuangansampah', 'kdcarapembuangansampah');
    }

    public function manfaatmataair()
    {
        return $this->belongsTo(MasterManfaatMataAir::class, 'kdmanfaatmataair', 'kdmanfaatmataair');
    }
}