<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisBahanGalian extends Model
{
    protected $table = 'master_jenisbahan_galian';
    protected $primaryKey = 'kdjenisbahan_galian';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjenisbahan_galian', 'jenisbahan_galian'];
}
