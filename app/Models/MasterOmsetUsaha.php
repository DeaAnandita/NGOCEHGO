<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterOmsetUsaha extends Model
{
    protected $table = 'master_omsetusaha';
    protected $primaryKey = 'kdomsetusaha';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdomsetusaha', 'omsetusaha'];
}
