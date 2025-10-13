<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterBahanBakarMemasak extends Model
{
    protected $table = 'master_bahanbakarmemasak';
    protected $primaryKey = 'kdbahanbakarmemasak';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdbahanbakarmemasak', 'bahanbakarmemasak'];
}
