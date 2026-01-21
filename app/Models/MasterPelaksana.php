<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPelaksana extends Model
{
    protected $table = 'master_pelaksana';
    protected $primaryKey = 'kdpelaksana';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['kdpelaksana', 'pelaksana'];
}
