<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSejahteraKeluarga extends Model
{
    use HasFactory;

    protected $table = 'data_sejahterakeluarga';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // isi semua kolom
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(['no_kk'], array_map(fn($i) => "sejahterakeluarga_$i", range(61, 68)));
    }


    /**
     * Relasi ke tabel data_keluarga
     * Setiap data sejahtera keluarga milik satu keluarga (by NO_KK)
     */
    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_kk', 'no_kk');
    }
}