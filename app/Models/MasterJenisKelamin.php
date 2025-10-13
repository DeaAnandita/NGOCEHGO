<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisKelamin extends Model
{
    protected $table = 'master_jeniskelamin';
    protected $primaryKey = 'kdjeniskelamin';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjeniskelamin', 'jeniskelamin'];
}
