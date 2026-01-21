<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterUnitKeputusan extends Model
{
    protected $table = 'master_unit_keputusan';
    protected $primaryKey = 'kdunit';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdunit',
        'unit_keputusan'
    ];
}
