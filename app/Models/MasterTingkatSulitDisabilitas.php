<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTingkatSulitDisabilitas extends Model
{
    protected $table = 'master_tingkatsulitdisabilitas';
    protected $primaryKey = 'kdtingkatsulitdisabilitas';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdtingkatsulitdisabilitas', 'tingkatsulitdisabilitas'];
}
