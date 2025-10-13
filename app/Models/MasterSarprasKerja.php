<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSarprasKerja extends Model
{
    protected $table = 'master_sarpraskerja';
    protected $primaryKey = 'kdsarpraskerja';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdsarpraskerja', 'sarpraskerja'];
}
