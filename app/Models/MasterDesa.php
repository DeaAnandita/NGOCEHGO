<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDesa extends Model
{
    protected $table = 'master_desa';
    protected $primaryKey = 'kddesa';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkecamatan', 'kddesa', 'desa'];

    public function kecamatan() {
        return $this->belongsTo(MasterKecamatan::class, 'kdkecamatan', 'kdkecamatan');
    }


}
