<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisPemilik extends Model
{
    protected $table = 'master_jenispemilik';
    protected $primaryKey = 'kdjenispemilik';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdjenispemilik',
        'jenispemilik'
    ];
}
