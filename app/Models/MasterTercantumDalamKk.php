<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTercantumDalamKk extends Model
{
    protected $table = 'master_tercantumdalamkk';
    protected $primaryKey = 'kdtercantumdalamkk';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdtercantumdalamkk', 'tercantumdalamkk'];
}
