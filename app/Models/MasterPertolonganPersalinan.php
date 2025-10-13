<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPertolonganPersalinan extends Model
{
    protected $table = 'master_pertolonganpersalinan';
    protected $primaryKey = 'kdpertolonganpersalinan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdpertolonganpersalinan', 'pertolonganpersalinan'];
}
