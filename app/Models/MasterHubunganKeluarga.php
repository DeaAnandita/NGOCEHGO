<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterHubunganKeluarga extends Model
{
    protected $table = 'master_hubungankeluarga';
    protected $primaryKey = 'kdhubungankeluarga';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdhubungankeluarga', 'hubungankeluarga'];
}
