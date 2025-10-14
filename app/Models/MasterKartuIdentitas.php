<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKartuIdentitas extends Model
{
    protected $table = 'master_kartuidentitas';
    protected $primaryKey = 'kdkartuidentitas';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkartuidentitas', 'kartuidentitas'];
}