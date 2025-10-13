<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKabupaten extends Model
{
    protected $table = 'master_kabupaten';
    protected $primaryKey = 'kdkabupaten';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdprovinsi', 'kdkabupaten', 'kabupaten', ];

    public function provinsi() {
        return $this->belongsTo(MasterProvinsi::class, 'kdprovinsi', 'kdprovinsi');
    }
    public function kecamatan() {
        return $this->hasMany(MasterKecamatan::class, 'kdkabupaten', 'kdkabupaten');
    }
}

