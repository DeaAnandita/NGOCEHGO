<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKonflikSosial extends Model
{
    use HasFactory;

    protected $table = 'data_konfliksosial';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // isi semua kolom
    protected $fillable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(['no_kk'], array_map(fn($i) => "konfliksosial_$i", range(1, 32)));
    }


    /**
     * Relasi ke tabel data_keluarga
     * Setiap data konflik sosial milik satu keluarga (by NO_KK)
     */
    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_kk', 'no_kk');
    }
}