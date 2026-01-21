<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAparat extends Model
{
    protected $table = 'master_aparat';
    protected $primaryKey = 'kdaparat';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdaparat',
        'aparat'
    ];
}
