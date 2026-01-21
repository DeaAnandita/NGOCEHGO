<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuTanahDesa extends Model
{
    protected $table = 'buku_tanahdesa';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kdtanahdesa',
        'tanggaltanahdesa',
        'kdjenispemilik',
        'pemiliktanahdesa',
        'kdpemilik',
        'luastanahdesa',
        'kdstatushaktanah',
        'kdpenggunaantanah',
        'kdmutasitanah',
        'tanggalmutasitanahdesa',
        'keterangantanahdesa',
        'fototanahdesa',
        'userinput',
        'inputtime'
    ];

    protected $casts = [
        'tanggaltanahdesa' => 'date',
        'tanggalmutasitanahdesa' => 'date',
        'luastanahdesa' => 'decimal:2',
        'inputtime' => 'datetime'
    ];

    public function statusHak()
    {
        return $this->belongsTo(MasterStatusHakTanah::class, 'kdstatushaktanah');
    }

    public function penggunaan()
    {
        return $this->belongsTo(MasterPenggunaanTanah::class, 'kdpenggunaantanah');
    }

    public function mutasi()
    {
        return $this->belongsTo(MasterMutasiTanah::class, 'kdmutasitanah');
    }

    public function jenisPemilik()
    {
        return $this->belongsTo(MasterJenisPemilik::class, 'kdjenispemilik');
    }
}
