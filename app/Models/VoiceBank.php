<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceBank extends Model
{
    protected $table = 'voice_bank';
    protected $fillable = ['session_id', 'voice_fingerprint', 'voice_file'];
}