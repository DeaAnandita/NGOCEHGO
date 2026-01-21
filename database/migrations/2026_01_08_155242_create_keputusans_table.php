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
        Schema::create('keputusan', function (Blueprint $table) {
            $table->id();

            $table->integer('kdjenis');
            $table->integer('kdunit');
            $table->integer('kdperiode');
            $table->integer('kdstatus');
            $table->integer('kdmetode');

            $table->string('nomor_sk')->nullable();
            $table->string('judul_keputusan');
            $table->date('tanggal_keputusan');

            $table->integer('kdjabatan');

            $table->timestamps();

            // =====================
            // FOREIGN KEY
            // =====================
            $table->foreign('kdjenis')->references('kdjenis')->on('master_jenis_keputusan');
            $table->foreign('kdunit')->references('kdunit')->on('master_unit_keputusan');
            $table->foreign('kdperiode')->references('kdperiode')->on('master_periode_kelembagaan');
            $table->foreign('kdstatus')->references('kdstatus')->on('master_status_keputusan');
            $table->foreign('kdmetode')->references('kdmetode')->on('master_metode_keputusan');
            $table->foreign('kdjabatan')->references('kdjabatan')->on('master_jabatan_kelembagaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keputusans');
    }
};
