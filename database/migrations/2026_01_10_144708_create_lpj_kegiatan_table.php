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
        Schema::create('lpj_kegiatan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('kegiatan_id');
            $table->decimal('total_anggaran', 15, 2);
            $table->decimal('total_realisasi', 15, 2);
            $table->decimal('sisa_anggaran', 15, 2);

            $table->string('file_lpj')->nullable();
            $table->integer('status');

            $table->timestamps();

            $table->foreign('kegiatan_id')->references('id')->on('kegiatan')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpj_kegiatan');
    }
};
