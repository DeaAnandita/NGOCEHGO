<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('data_sosialekonomi', function (Blueprint $table) {
            $table->string('nik', 16)->primary(); // PK dan FK ke data_penduduk

            // Relasi ke master tabel (nullable, sesuai pilihan form, tipe integer untuk kode master)
            $table->integer('kdpartisipasisekolah')->nullable(); // relasi ke master_paritisipasisekolah
            $table->integer('kdtingkatsulitdisabilitas')->nullable(); // relasi ke master_tingkatsulitdisabilitas
            $table->integer('kdstatuskedudukankerja')->nullable(); // relasi ke master_statuskedudukankerja
            $table->integer('kdijasahterakhir')->nullable(); // relasi ke master_ijasahterakhir
            $table->integer('kdpenyakitkronis')->nullable(); // relasi ke master_penyakitkronis
            $table->integer('kdpendapatanperbulan')->nullable(); // relasi ke master_pendapatanperbulan
            $table->integer('kdjenisdisabilitas')->nullable(); // relasi ke master_jenisdisabilitas
            $table->integer('kdlapanganusaha')->nullable(); // relasi ke master_lapanganusaha
            $table->integer('kdimunisasi')->nullable(); // relasi ke master_imunisasi

            // Foreign key utama
            $table->foreign('nik')->references('nik')->on('data_penduduk')->onDelete('cascade');

            // Foreign keys ke master tables (asumsi PK master adalah integer dengan nama kolom sesuai kd)
            $table->foreign('kdpartisipasisekolah')->references('kdpartisipasisekolah')->on('master_partisipasisekolah')->onDelete('set null');
            $table->foreign('kdtingkatsulitdisabilitas')->references('kdtingkatsulitdisabilitas')->on('master_tingkatsulitdisabilitas')->onDelete('set null');
            $table->foreign('kdstatuskedudukankerja')->references('kdstatuskedudukankerja')->on('master_statuskedudukankerja')->onDelete('set null');
            $table->foreign('kdijasahterakhir')->references('kdijasahterakhir')->on('master_ijasahterakhir')->onDelete('set null');
            $table->foreign('kdpenyakitkronis')->references('kdpenyakitkronis')->on('master_penyakitkronis')->onDelete('set null');
            $table->foreign('kdpendapatanperbulan')->references('kdpendapatanperbulan')->on('master_pendapatanperbulan')->onDelete('set null');
            $table->foreign('kdjenisdisabilitas')->references('kdjenisdisabilitas')->on('master_jenisdisabilitas')->onDelete('set null');
            $table->foreign('kdlapanganusaha')->references('kdlapanganusaha')->on('master_lapanganusaha')->onDelete('set null');
            $table->foreign('kdimunisasi')->references('kdimunisasi')->on('master_imunisasi')->onDelete('set null');
        });
    }

    /**
     * Hapus tabel.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sosialekonomi');
    }
};