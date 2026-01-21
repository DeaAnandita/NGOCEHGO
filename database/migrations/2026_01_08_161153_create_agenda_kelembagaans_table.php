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
        Schema::create('agenda_kelembagaan', function (Blueprint $table) {
            $table->id();

            $table->integer('kdjenis');
            $table->integer('kdunit');
            $table->integer('kdstatus');
            $table->integer('kdtempat');
            $table->integer('kdperiode');

            $table->string('judul_agenda');
            $table->text('uraian_agenda')->nullable();

            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();

            $table->timestamps();

            // =====================
            // FOREIGN KEY
            // =====================
            $table->foreign('kdjenis')->references('kdjenis')->on('master_jenis_agenda');
            $table->foreign('kdunit')->references('kdunit')->on('master_unit_keputusan');
            $table->foreign('kdstatus')->references('kdstatus')->on('master_status_agenda');
            $table->foreign('kdtempat')->references('kdtempat')->on('master_tempat_agenda');
            $table->foreign('kdperiode')->references('kdperiode')->on('master_periode_kelembagaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_kelembagaans');
    }
};
