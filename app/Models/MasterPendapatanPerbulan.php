<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPendapatanPerbulan extends Model
{
    protected $table = 'master_pendapatanperbulan';
    protected $primaryKey = 'kdpendapatanperbulan';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdpendapatanperbulan', 'pendapatanperbulan'];
}
