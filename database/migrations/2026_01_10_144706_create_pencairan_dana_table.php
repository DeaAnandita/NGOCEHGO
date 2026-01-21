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
        Schema::create('pencairan_dana', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('kegiatan_id');
            $table->date('tanggal_cair');
            $table->decimal('jumlah', 15, 2);

            $table->string('no_sp2d')->nullable();
            $table->string('bukti_transfer')->nullable();

            $table->timestamps();

            $table->foreign('kegiatan_id')->references('id')->on('kegiatan')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairan_dana');
    }
};
