<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterHubunganKepalaKeluarga extends Model
{
    protected $table = 'master_hubungankepalakeluarga';
    protected $primaryKey = 'kdhubungankepalakeluarga';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdhubungankepalakeluarga', 'hubungankepalakeluarga'];
}
