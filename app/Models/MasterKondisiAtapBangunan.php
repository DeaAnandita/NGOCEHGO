<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKondisiAtapBangunan extends Model
{
    protected $table = 'master_kondisiatapbangunan';
    protected $primaryKey = 'kdkondisiatapbangunan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkondisiatapbangunan', 'kondisiatapbangunan'];
}
