<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterBantuan extends Model
{
    protected $table = 'master_bantuan';
    protected $primaryKey = 'kdbantuan';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['kdbantuan', 'bantuan'];
}
