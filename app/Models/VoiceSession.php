<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceSession extends Model
{
    use HasFactory;

    protected $fillable = ['session_type', 'user_id', 'no_kk', 'nik', 'started_at', 'finished_at'];

    public function answers()
    {
        return $this->hasMany(VoiceAnswer::class, 'voice_session_id');
    }
}
