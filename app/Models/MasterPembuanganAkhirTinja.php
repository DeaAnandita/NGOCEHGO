<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPembuanganAkhirTinja extends Model
{
    protected $table = 'master_pembuanganakhirtinja';
    protected $primaryKey = 'kdpembuanganakhirtinja';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdpembuanganakhirtinja', 'pembuanganakhirtinja'];
}
