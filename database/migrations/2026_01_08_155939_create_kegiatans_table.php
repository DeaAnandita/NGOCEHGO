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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('keputusan_id')->nullable();
            $table->integer('kdjenis');
            $table->integer('kdunit');
            $table->integer('kdperiode');
            $table->integer('kdstatus');
            $table->integer('kdsumber');

            $table->string('nama_kegiatan');
            $table->text('lokasi');
            $table->decimal('pagu_anggaran', 15, 2);

            $table->date('tgl_mulai');
            $table->date('tgl_selesai')->nullable();

            $table->timestamps();

            // =======================
            // FOREIGN KEY CONSTRAINT
            // =======================
            $table->foreign('keputusan_id')->references('id')->on('keputusan')->nullOnDelete();

            $table->foreign('kdjenis')->references('kdjenis')->on('master_jenis_kegiatan');
            $table->foreign('kdunit')->references('kdunit')->on('master_unit_keputusan');
            $table->foreign('kdperiode')->references('kdperiode')->on('master_periode_kelembagaan');
            $table->foreign('kdstatus')->references('kdstatus')->on('master_status_kegiatan');
            $table->foreign('kdsumber')->references('kdsumber')->on('master_sumber_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
