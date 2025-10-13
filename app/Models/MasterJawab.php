<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawab extends Model
{
    protected $table = 'master_jawab';
    protected $primaryKey = 'kdjawab';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawab', 'jawab'];
}
