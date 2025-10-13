<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTypeJawab extends Model
{
    protected $table = 'master_typejawab';
    protected $primaryKey = 'kdtypejawab';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdtypejawab', 'typejawab'];

    public function pembangunanKeluarga() {
        return $this->hasMany(MasterPembangunanKeluarga::class, 'kdtypejawab', 'kdtypejawab');
    }
}
