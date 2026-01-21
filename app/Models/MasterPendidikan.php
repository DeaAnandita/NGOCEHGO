<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPendidikan extends Model
{
    protected $table = 'master_pendidikan';
    protected $primaryKey = 'kdpendidikan';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['kdpendidikan', 'pendidikan'];
}
