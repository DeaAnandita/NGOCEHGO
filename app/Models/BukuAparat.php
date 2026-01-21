<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuAparat extends Model
{
    protected $table = 'buku_aparat';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kdaparat',
        'namaaparat', // ✅ tambah ini
        'nipaparat',
        'nik',
        'pangkataparat',
        'nomorpengangkatan',
        'tanggalpengangkatan',
        'keteranganaparatdesa',
        'fotopengangkatan',
        'statusaparatdesa',
        'userinput',
        'inputtime'
    ];

    protected $casts = [
        'tanggalpengangkatan' => 'date',
        'inputtime' => 'datetime',
    ];

    public function masterAparat()
    {
        return $this->belongsTo(MasterAparat::class, 'kdaparat', 'kdaparat');
    }
}
