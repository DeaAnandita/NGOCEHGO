<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPapanNama extends Model
{
    protected $table = 'master_papannama';
    protected $primaryKey = 'kdpapannama';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdpapannama',
        'papannama'
    ];
}
