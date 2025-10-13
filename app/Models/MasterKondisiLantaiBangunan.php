<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKondisiLantaiBangunan extends Model
{
    protected $table = 'master_kondisilantaibangunan';
    protected $primaryKey = 'kdkondisilantaibangunan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkondisilantaibangunan', 'kondisilantaibangunan'];
}
