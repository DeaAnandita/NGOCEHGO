<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKonflikSosial extends Model
{
    protected $table = 'master_konfliksosial';
    protected $primaryKey = 'kdkonfliksosial';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdkonfliksosial', 'konfliksosial'];
}
