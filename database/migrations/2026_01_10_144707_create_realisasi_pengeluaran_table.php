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
        Schema::create('realisasi_pengeluaran', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pencairan_id');
            $table->date('tanggal');
            $table->string('uraian');
            $table->decimal('jumlah', 15, 2);
            $table->string('bukti')->nullable();

            $table->timestamps();

            $table->foreign('pencairan_id')->references('id')->on('pencairan_dana')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_pengeluaran');
    }
};
