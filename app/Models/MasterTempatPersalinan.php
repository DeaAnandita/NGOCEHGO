<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTempatPersalinan extends Model
{
    protected $table = 'master_tempatpersalinan';
    protected $primaryKey = 'kdtempatpersalinan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdtempatpersalinan', 'tempatpersalinan'];
}
