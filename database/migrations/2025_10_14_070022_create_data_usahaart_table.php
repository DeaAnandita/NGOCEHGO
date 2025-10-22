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
        Schema::create('data_usahaart', function (Blueprint $table) {
            $table->string('nik', 16)->primary(); // PK dan FK ke data_penduduk

            // Relasi ke master tabel (integer untuk kode master, nullable kecuali ditentukan)
            $table->integer('kdlapanganusaha')->nullable(); // relasi ke master_lapanganusaha
            $table->integer('kdtempatusaha')->nullable(); // relasi ke master_tempatusaha (asumsi kd untuk kode)
            $table->integer('kdomsetusaha')->nullable(); // relasi ke master_omsetusaha

            // Field form lainnya
            $table->integer('usahaart_jumlahpekerja'); // required, angka
            $table->string('usahaart_namausaha'); // required, string

            // Foreign key utama
            $table->foreign('nik')->references('nik')->on('data_penduduk')->onDelete('cascade');

            // Foreign keys ke master tables (asumsi PK master adalah integer dengan nama kolom sesuai kd)
            $table->foreign('kdlapanganusaha')->references('kdlapanganusaha')->on('master_lapanganusaha')->onDelete('set null');
            $table->foreign('kdtempatusaha')->references('kdtempatusaha')->on('master_tempatusaha')->onDelete('set null');
            $table->foreign('kdomsetusaha')->references('kdomsetusaha')->on('master_omsetusaha')->onDelete('set null');
        });
    }

    /**
     * Hapus tabel.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_usahaart');
    }
};