<?php

// database/migrations/xxxx_xx_xx_create_voice_bank_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('voice_bank', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique(); // identifier unik per responden
            $table->text('voice_fingerprint');     // hash gelombang suara
            $table->string('voice_file');          // path file .wav di storage
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voice_bank');
    }
};