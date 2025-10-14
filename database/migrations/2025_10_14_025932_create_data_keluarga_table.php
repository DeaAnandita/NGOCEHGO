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
        Schema::create('data_keluarga', function (Blueprint $table) {
            $table->string('no_kk', 16)->primary(); // PK, 16 digit, unique, required

            // relasi ke master_jenis_mutasi
            $table->integer('kdmutasimasuk')->nullable();

            $table->date('keluarga_tanggalmutasi')->default(DB::raw('CURRENT_DATE')); // otomatis hari ini
            $table->string('keluarga_kepalakeluarga'); // required
            $table->integer('kddusun')->nullable(); // relasi ke master_dusun
            $table->string('keluarga_rw', 3);
            $table->string('keluarga_rt', 3);
            $table->text('keluarga_alamatlengkap');

            // Wilayah datang (aktif kalau jenis mutasi = datang)
            $table->integer('kdprovinsi')->nullable();  // relasi ke master_provinsi
            $table->integer('kdkabupaten')->nullable(); // relasi ke master_kabupaten
            $table->integer('kdkecamatan')->nullable(); // relasi ke master_kecamatan
            $table->integer('kddesa')->nullable();      // relasi ke master_desa

            $table->timestamps();

            // Foreign key (opsional, bisa diaktifkan nanti)
            $table->foreign('kdmutasimasuk')->references('kdmutasimasuk')->on('master_mutasimasuk')->onDelete('set null');
            $table->foreign('kddusun')->references('kddusun')->on('master_dusun')->onDelete('set null');
            $table->foreign('kdprovinsi')->references('kdprovinsi')->on('master_provinsi')->onDelete('set null');
            $table->foreign('kdkabupaten')->references('kdkabupaten')->on('master_kabupaten')->onDelete('set null');
            $table->foreign('kdkecamatan')->references('kdkecamatan')->on('master_kecamatan')->onDelete('set null');
            $table->foreign('kddesa')->references('kddesa')->on('master_desa')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_keluarga');
    }
};
