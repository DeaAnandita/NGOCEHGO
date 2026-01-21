<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuInventaris extends Model
{
    protected $table = 'buku_inventaris';
    protected $primaryKey = 'kdinventaris';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kdinventaris',
        'inventaris_tanggal',
        'kdpengguna',
        'anak',
        'inventaris_volume',
        'inventaris_hapus',
        'kdsatuanbarang',
        'inventaris_identitas',
        'kdasalbarang',
        'barangasal',
        'inventaris_harga',
        'inventaris_keterangan',
        'inventaris_foto',
        'userinput',
        'inputtime'
    ];

    public function pengguna()
    {
        return $this->belongsTo(MasterPengguna::class, 'kdpengguna');
    }

    public function satuan()
    {
        return $this->belongsTo(MasterSatuanBarang::class, 'kdsatuanbarang');
    }

    public function asalBarang()
    {
        return $this->belongsTo(MasterAsalBarang::class, 'kdasalbarang');
    }
}
