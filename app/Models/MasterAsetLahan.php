<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAsetLahan extends Model
{
    protected $table = 'master_asetlahan';
    protected $primaryKey = 'kdasetlahan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdasetlahan', 'asetlahan'];
}
