<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAsetPerikanan extends Model
{
    protected $table = 'master_asetperikanan';
    protected $primaryKey = 'kdasetperikanan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdasetperikanan', 'asetperikanan'];
}
