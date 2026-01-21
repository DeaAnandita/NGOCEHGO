<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisTKD extends Model
{
    protected $table = 'master_jenistkd';
    protected $primaryKey = 'kdjenistkd';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjenistkd',
        'jenistkd'
    ];
}
