<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAsetPerikanan extends Model
{
    use HasFactory;

    protected $table = 'data_asetperikanan';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // isi semua kolom
   

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(['no_kk'], array_map(fn($i) => "asetperikanan_$i", range(1, 6)));
    }


    /**
     * Relasi ke tabel data_penduduk
     * Setiap data lembaga ekonomi milik satu penduduk (by no_kk)
     */
    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_kk', 'no_kk');
    }
}