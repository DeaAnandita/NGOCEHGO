<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPatok extends Model
{
    protected $table = 'master_patok';
    protected $primaryKey = 'kdpatok';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdpatok',
        'patok'
    ];
}
