<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKecamatan extends Model
{
    protected $table = 'master_kecamatan';
    protected $primaryKey = 'kdkecamatan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkabupaten', 'kdkecamatan', 'kecamatan'];

    public function kabupaten() {
        return $this->belongsTo(MasterKabupaten::class, 'kdkabupaten', 'kdkabupaten');
    }
    public function desa() {
        return $this->hasMany(MasterDesa::class, 'kdkecamatan', 'kdkecamatan');
    }
}
