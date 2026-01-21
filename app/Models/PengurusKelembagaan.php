<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengurusKelembagaan extends Model
{
    protected $table = 'pengurus_kelembagaan';
    protected $primaryKey = 'id_pengurus';

    protected $fillable = [
        'nomor_induk',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'email',
        'kdjabatan',
        'kdunit',
        'kdperiode',          // tahun awal
        'kdperiode_akhir',    // tahun akhir
        'kdstatus',
        'kdjenissk',
        'no_sk',
        'tanggal_sk',
        'foto',
        'tanda_tangan',
        'keterangan'
    ];

    // ================= RELATIONS =================

    public function jabatan()
    {
        return $this->belongsTo(
            MasterJabatanKelembagaan::class,
            'kdjabatan',
            'kdjabatan'
        );
    }

    public function unit()
    {
        return $this->belongsTo(
            MasterUnitKelembagaan::class,
            'kdunit',
            'kdunit'
        );
    }

    // Periode awal
    public function periodeAwal()
    {
        return $this->belongsTo(
            MasterPeriodeKelembagaan::class,
            'kdperiode',
            'kdperiode'
        );
    }

    // Periode akhir
    public function periodeAkhir()
    {
        return $this->belongsTo(
            MasterPeriodeKelembagaanAkhir::class,
            'kdperiode_akhir',
            'kdperiode'
        );
    }

    public function status()
    {
        return $this->belongsTo(
            MasterStatusPengurusKelembagaan::class,
            'kdstatus',
            'kdstatus'
        );
    }

    public function jenisSk()
    {
        return $this->belongsTo(
            MasterJenisSkKelembagaan::class,
            'kdjenissk',
            'kdjenissk'
        );
    }
}
