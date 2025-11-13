<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'frequency_signature',
    ];
}
