<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterProgramSerta extends Model
{
    protected $table = 'master_programserta';
    protected $primaryKey = 'kdprogramserta';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdprogramserta', 'programserta'];
}
