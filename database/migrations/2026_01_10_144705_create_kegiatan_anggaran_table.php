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
        Schema::create('kegiatan_anggaran', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('anggaran_id');   
            $table->unsignedBigInteger('kegiatan_id');
            $table->integer('kdsumber');
            $table->decimal('nilai_anggaran', 15, 2);

            $table->timestamps();

            $table->foreign('anggaran_id')->references('id')->on('anggaran_kelembagaan')->cascadeOnDelete();
            $table->foreign('kegiatan_id')->references('id')->on('kegiatan')->cascadeOnDelete();
            $table->foreign('kdsumber')->references('kdsumber')->on('master_sumber_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_anggaran');
    }
};
