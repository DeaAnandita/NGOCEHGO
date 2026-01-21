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
        Schema::create('buku_keputusan', function (Blueprint $table) {

            $table->string('kd_keputusan')->primary();
            $table->string('nomor_keputusan');
            $table->date('tanggal_keputusan');
            $table->string('judul_keputusan');

            // RELASI KE MASTER KEPUTUSAN UMUM
            $table->integer('kdjeniskeputusan_umum');

            $table->text('uraian_keputusan')->nullable();
            $table->string('keterangan_keputusan')->nullable();
            $table->string('file_keputusan')->nullable();

            $table->string('userinput');
            $table->timestamp('inputtime')->useCurrent();

            $table->foreign('kdjeniskeputusan_umum')
                ->references('kdjeniskeputusan_umum')
                ->on('master_jeniskeputusan_umum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_keputusans');
    }
};
