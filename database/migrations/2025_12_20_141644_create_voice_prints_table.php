<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voice_prints', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 16);
            $table->foreign('no_kk')
                ->references('no_kk')
                ->on('data_keluarga')
                ->onDelete('cascade');
            $table->json('embedding');           // ← nama kolom sesuai model
            $table->string('filename')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_prints'); // ← harus sama dengan up()
    }
};