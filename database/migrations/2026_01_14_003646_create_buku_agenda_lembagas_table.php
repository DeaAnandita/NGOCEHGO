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
        Schema::create('buku_agendalembaga', function (Blueprint $table) {

            $table->string('kdagendalembaga')->primary();

            $table->integer('kdjenisagenda_umum')->nullable();

            $table->date('agendalembaga_tanggal')->nullable();
            $table->string('agendalembaga_nomorsurat')->nullable();
            $table->date('agendalembaga_tanggalsurat')->nullable();

            $table->string('agendalembaga_identitassurat')->nullable();
            $table->text('agendalembaga_isisurat')->nullable();
            $table->text('agendalembaga_keterangan')->nullable();

            $table->string('agendalembaga_file')->nullable();
            $table->string('userinput')->nullable();
            $table->timestamp('inputtime')->useCurrent();

            $table->foreign('kdjenisagenda_umum')
                ->references('kdjenisagenda_umum')
                ->on('master_jenisagenda_umum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_agenda_lembagas');
    }
};
