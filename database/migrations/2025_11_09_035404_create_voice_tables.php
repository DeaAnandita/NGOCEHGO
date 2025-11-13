<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_voice_tables.php
    public function up(): void
    {
        Schema::create('voice_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_type'); // keluarga / penduduk
            $table->string('no_kk', 16)->nullable();
            $table->string('nik', 16)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('no_kk')->references('no_kk')->on('data_keluarga')->onDelete('cascade');
        });

        Schema::create('voice_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voice_session_id')->constrained()->cascadeOnDelete();
            $table->string('module');
            $table->string('field');
            $table->text('answer_text')->nullable();
            $table->string('answer_audio')->nullable();
            $table->string('audio_fingerprint', 64)->nullable();
            $table->boolean('is_edited')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voice_tables');
    }
};
