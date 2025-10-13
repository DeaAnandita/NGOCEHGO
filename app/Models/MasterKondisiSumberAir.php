<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKondisiSumberAir extends Model
{
    protected $table = 'master_kondisisumberair';
    protected $primaryKey = 'kdkondisisumberair';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkondisisumberair', 'kondisisumberair'];
}
