<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterManfaatMataAir extends Model
{
    protected $table = 'master_manfaatmataair';
    protected $primaryKey = 'kdmanfaatmataair';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdmanfaatmataair', 'manfaatmataair'];
}
