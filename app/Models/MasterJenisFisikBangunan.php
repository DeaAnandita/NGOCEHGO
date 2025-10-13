<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisFisikBangunan extends Model
{
    protected $table = 'master_jenisfisikbangunan';
    protected $primaryKey = 'kdjenisfisikbangunan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjenisfisikbangunan', 'jenisfisikbangunan'];
}
