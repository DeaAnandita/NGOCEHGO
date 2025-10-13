<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterFasilitasTempatBab extends Model
{
    protected $table = 'master_fasilitastempatbab';
    protected $primaryKey = 'kdfasilitastempatbab';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdfasilitastempatbab', 'fasilitastempatbab'];
}
