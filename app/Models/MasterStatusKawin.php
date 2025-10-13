<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusKawin extends Model
{
    protected $table = 'master_statuskawin';
    protected $primaryKey = 'kdstatuskawin';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdstatuskawin', 'statuskawin'];
}
