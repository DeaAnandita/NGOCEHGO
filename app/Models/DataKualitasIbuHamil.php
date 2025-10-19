<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKualitasIbuHamil extends Model
{
    use HasFactory;

    protected $table = 'data_kualitasibuhamil';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // isi semua kolom
    protected $fillable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(['no_kk'], array_map(fn($i) => "kualitasibuhamil_$i", range(1, 13)));
    }


    /**
     * Relasi ke tabel data_keluarga
     * Setiap data aset keluarga milik satu keluarga (by no_kk)
     */
    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_kk', 'no_kk');
    }
}