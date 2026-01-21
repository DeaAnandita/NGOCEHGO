<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKriteriaKeputusan extends Model
{
    protected $table = 'master_kriteria_keputusan';
    protected $primaryKey = 'kdkriteria';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdkriteria',
        'kriteria'
    ];
}
