<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJawabBangun extends Model
{
    protected $table = 'master_jawabbangun';
    protected $primaryKey = 'kdjawabbangun';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdjawabbangun', 'jawabbangun'];
}
