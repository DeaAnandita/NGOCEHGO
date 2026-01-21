<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKaderBidang extends Model
{
    protected $table = 'master_kader_bidang';
    protected $primaryKey = 'kdbidang';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['kdbidang', 'bidang'];
}
