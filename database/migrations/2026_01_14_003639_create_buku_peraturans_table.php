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
        Schema::create('buku_peraturans', function (Blueprint $table) {
            $table->string('kdperaturan')->primary();
            $table->integer('kdjenisperaturandesa');
            $table->string('nomorperaturan');
            $table->string('judulpengaturan');
            $table->text('uraianperaturan')->nullable();
            $table->text('kesepakatanperaturan')->nullable();
            $table->string('keteranganperaturan')->nullable();
            $table->string('filepengaturan')->nullable();
            $table->string('userinput')->nullable();
            $table->timestamp('inputtime')->useCurrent();

            $table->foreign('kdjenisperaturandesa')
                ->references('kdjenisperaturandesa')
                ->on('master_jenisperaturandesa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_peraturans');
    }
};
