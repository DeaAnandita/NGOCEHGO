<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabLemmas extends Model
{
    protected $table = 'master_jawablemmas';
    protected $primaryKey = 'kdjawablemmas';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawablemmas', 'jawablemmas'];
}
