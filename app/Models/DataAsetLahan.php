<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAsetLahan extends Model
{
    use HasFactory;

    protected $table = 'data_asetlahan';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(['no_kk'], array_map(fn($i) => "asetlahan_$i", range(1, 10)));
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