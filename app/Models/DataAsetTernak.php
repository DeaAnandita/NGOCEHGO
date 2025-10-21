<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAsetTernak extends Model
{
    use HasFactory;

    protected $table = 'data_asetternak';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // isi semua kolom
    

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(['no_kk'], array_map(fn($i) => "asetternak_$i", range(1, 24)));
    }


    /**
     * Relasi ke tabel data_keluaga
     * Setiap data aset ternak milik satu keluarga (by NO_KK)
     */
    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_kk', 'no_kk');
    }
}