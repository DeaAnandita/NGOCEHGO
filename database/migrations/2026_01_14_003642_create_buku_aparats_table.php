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
        Schema::create('buku_aparat', function (Blueprint $table) {
            $table->bigIncrements('id'); // primary key

            // Relasi ke master_aparat
            $table->integer('kdaparat');
            $table->foreign('kdaparat')
                ->references('kdaparat')
                ->on('master_aparat')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('namaaparat', 150); // ✅ INI YANG KAMU MAKSUD "NAMA TIDAK ADA"

            // Data aparat
            $table->string('nipaparat', 50)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('pangkataparat', 100)->nullable();

            // SK / Pengangkatan
            $table->string('nomorpengangkatan', 100)->nullable();
            $table->date('tanggalpengangkatan')->nullable();

            // Keterangan
            $table->text('keteranganaparatdesa')->nullable();

            // Foto / Scan SK
            $table->string('fotopengangkatan')->nullable();

            // Status aparat desa
            $table->enum('statusaparatdesa', ['Aktif', 'Nonaktif'])->default('Aktif');

            // User input
            $table->string('userinput', 50);
            $table->timestamp('inputtime')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_aparats');
    }
};
