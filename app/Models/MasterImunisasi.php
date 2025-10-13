<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterImunisasi extends Model
{
    protected $table = 'master_imunisasi';
    protected $primaryKey = 'kdimunisasi';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdimunisasi', 'imunisasi'];
}
