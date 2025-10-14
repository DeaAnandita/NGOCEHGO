<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCaraPerolehanAir extends Model
{
    protected $table = 'master_caraperolehanair';
    protected $primaryKey = 'kdcaraperolehanair';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdcaraperolehanair', 'caraperolehanair'];
}
