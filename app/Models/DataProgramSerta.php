<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProgramSerta extends Model
{
    use HasFactory;

    protected $table = 'data_programserta';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // isi semua kolom

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(['nik'], array_map(fn($i) => "programserta_$i", range(1, 8)));
    }


    /**
     * Relasi ke tabel data_penduduk
     * Setiap data program serta milik satu penduduk (by NIK)
     */
    public function penduduk()
    {
        return $this->belongsTo(DataPenduduk::class, 'nik', 'nik');
    }
}