<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPembangunanKeluarga extends Model
{
    protected $table = 'master_pembangunankeluarga';
    protected $primaryKey = 'kdpembangunankeluarga';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdpembangunankeluarga', 'pembangunankeluarga', 'kdtypejawab'];

    public function typejawab() {
        return $this->belongsTo(MasterTypeJawab::class, 'kdtypejawab', 'kdtypejawab');
    }
}
