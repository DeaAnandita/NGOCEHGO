<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCaraPembuanganSampah extends Model
{
    protected $table = 'master_carapembuangansampah';
    protected $primaryKey = 'kdcarapembuangansampah';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['kdcarapembuangansampah', 'carapembuangansampah'];
}
