<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabKonflik extends Model
{
    protected $table = 'master_jawabkonflik';
    protected $primaryKey = 'kdjawabkonflik';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawabkonflik', 'jawabkonflik'];
}
