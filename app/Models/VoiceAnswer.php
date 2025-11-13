<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceAnswer extends Model
{
    protected $table = 'voice_answers';
    protected $fillable = [
        'question_key','transcript','value','audio_path','audio_hash'
    ];
}
