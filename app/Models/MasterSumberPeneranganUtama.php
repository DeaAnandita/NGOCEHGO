<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSumberPeneranganUtama extends Model
{
    protected $table = 'master_sumberpeneranganutama';
    protected $primaryKey = 'kdsumberpeneranganutama';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdsumberpeneranganutama', 'sumberpeneranganutama'];
}
