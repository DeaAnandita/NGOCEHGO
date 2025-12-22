<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voice_fingerprints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keluarga_id')->nullable(); // nanti diisi saat simpan
            $table->json('fingerprint'); // array float
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voice_fingerprints');
    }
};
