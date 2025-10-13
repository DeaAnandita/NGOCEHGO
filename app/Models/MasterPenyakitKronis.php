<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPenyakitKronis extends Model
{
    protected $table = 'master_penyakitkronis';
    protected $primaryKey = 'kdpenyakitkronis';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdpenyakitkronis', 'penyakitkronis'];
}
