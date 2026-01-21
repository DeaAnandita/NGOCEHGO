<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuProyek extends Model
{
    protected $table = 'buku_proyek';
    protected $primaryKey = 'reg';
    public $timestamps = false;

    protected $fillable = [
        'kdproyek',
        'proyek_tanggal',
        'kdkegiatan',
        'kdpelaksana',
        'kdlokasi',
        'proyek_nominal',
        'proyek_manfaat',
        'proyek_keterangan',
        'kdsumber',
        'userinput',
        'inputtime'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(MasterKegiatan::class, 'kdkegiatan');
    }
    public function pelaksana()
    {
        return $this->belongsTo(MasterPelaksana::class, 'kdpelaksana');
    }
    public function lokasi()
    {
        return $this->belongsTo(MasterLokasi::class, 'kdlokasi');
    }
    public function sumber()
    {
        return $this->belongsTo(MasterSumberDana::class, 'kdsumber');
    }
}
