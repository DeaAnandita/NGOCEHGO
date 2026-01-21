<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPerolehanTKD extends Model
{
    protected $table = 'master_perolehantkd';
    protected $primaryKey = 'kdperolehantkd';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdperolehantkd',
        'perolehantkd'
    ];
}
