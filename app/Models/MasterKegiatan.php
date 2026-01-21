<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKegiatan extends Model
{
    protected $table = 'master_kegiatan';
    protected $primaryKey = 'kdkegiatan';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['kdkegiatan', 'kegiatan'];
}
