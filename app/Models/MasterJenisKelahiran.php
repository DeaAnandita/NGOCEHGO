<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenisKelahiran extends Model
{
    protected $table = 'master_jeniskelahiran';
    protected $primaryKey = 'kdjeniskelahiran';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjeniskelahiran', 'jeniskelahiran'];
}
