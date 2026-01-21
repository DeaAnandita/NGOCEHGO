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
        Schema::create('buku_ekspedisi', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->string('kdekspedisi')->primary();

            $table->date('ekspedisi_tanggal')->nullable();
            $table->date('ekspedisi_tanggalsurat')->nullable();

            $table->string('ekspedisi_nomorsurat')->nullable();
            $table->string('ekspedisi_identitassurat')->nullable();

            $table->text('ekspedisi_isisurat')->nullable();
            $table->text('ekspedisi_keterangan')->nullable();

            $table->string('ekspedisi_file')->nullable(); // path / nama file

            $table->string('userinput')->nullable();
            $table->timestamp('inputtime')->useCurrent();

            // Index biar cepat
            $table->index('ekspedisi_tanggal');
            $table->index('ekspedisi_nomorsurat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_ekspedisis');
    }
};
