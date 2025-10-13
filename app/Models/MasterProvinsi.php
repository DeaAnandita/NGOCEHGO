<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterProvinsi extends Model
{
    protected $table = 'master_provinsi';
    protected $primaryKey = 'kdprovinsi';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdprovinsi', 'provinsi'];

    public function kabupaten() {
        return $this->hasMany(MasterKabupaten::class, 'kdprovinsi', 'kdprovinsi');
    }
}
