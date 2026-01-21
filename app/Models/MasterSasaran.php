<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSasaran extends Model
{
    protected $table = 'master_sasaran';
    protected $primaryKey = 'kdsasaran';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['kdsasaran', 'sasaran'];
}
