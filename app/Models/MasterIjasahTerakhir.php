<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterIjasahTerakhir extends Model
{
    protected $table = 'master_ijasahterakhir';
    protected $primaryKey = 'kdijasahterakhir';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdijasahterakhir', 'ijasahterakhir'];
}
