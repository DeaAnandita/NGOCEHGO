<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTempatUsaha extends Model
{
    protected $table = 'master_tempatusaha';
    protected $primaryKey = 'kdtempatusaha';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdtempatusaha', 'tempatusaha'];
}

