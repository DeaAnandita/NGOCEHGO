<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPartisipasiSekolah extends Model
{
    protected $table = 'master_partisipasisekolah';
    protected $primaryKey = 'kdpartisipasisekolah';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdpartisipasisekolah', 'partisipasisekolah'];
}
