<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabLahan extends Model
{
    protected $table = 'master_jawablahan';
    protected $primaryKey = 'kdjawablahan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawablahan', 'jawablahan'];
}
