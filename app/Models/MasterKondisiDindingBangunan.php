<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKondisiDindingBangunan extends Model
{
    protected $table = 'master_kondisidindingbangunan';
    protected $primaryKey = 'kdkondisidindingbangunan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkondisidindingbangunan', 'kondisidindingbangunan'];
}
