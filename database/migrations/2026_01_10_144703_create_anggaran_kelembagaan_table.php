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
        Schema::create('anggaran_kelembagaan', function (Blueprint $table) {
            $table->id();

            $table->integer('kdunit');
            $table->integer('kdperiode');
            $table->integer('kdsumber');

            $table->decimal('total_anggaran', 15, 2);
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('kdunit')->references('kdunit')->on('master_unit_keputusan');
            $table->foreign('kdperiode')->references('kdperiode')->on('master_periode_kelembagaan');
            $table->foreign('kdsumber')->references('kdsumber')->on('master_sumber_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran_kelembagaan');
    }
};
