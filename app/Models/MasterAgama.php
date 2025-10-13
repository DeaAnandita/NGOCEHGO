<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAgama extends Model
{
    protected $table = 'master_agama';
    protected $primaryKey = 'kdagama';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdagama', 'agama'];
}
