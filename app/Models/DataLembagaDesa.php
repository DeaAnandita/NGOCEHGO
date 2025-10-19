<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLembagaDesa extends Model
{
    use HasFactory;

    protected $table = 'data_lembagadesa';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // isi semua kolom
    protected $fillable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(['nik'], array_map(fn($i) => "lemdes_$i", range(1, 9)));
    }


    /**
     * Relasi ke tabel data_penduduk
     * Setiap data lembaga desa milik satu penduduk (by NIK)
     */
    public function penduduk()
    {
        return $this->belongsTo(DataPenduduk::class, 'nik', 'nik');
    }
}