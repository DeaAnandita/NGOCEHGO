<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterInventaris extends Model
{
    protected $table = 'master_inventaris';
    protected $primaryKey = 'kdinventaris';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdinventaris', 'inventaris'];
}

