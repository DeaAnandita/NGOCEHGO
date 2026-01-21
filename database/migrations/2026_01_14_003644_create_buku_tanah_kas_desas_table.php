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
        Schema::create('buku_tanah_kas_desa', function (Blueprint $table) {
            $table->string('kdtanahkasdesa')->primary();

            $table->string('asaltanahkasdesa')->nullable();
            $table->string('sertifikattanahkasdesa')->nullable();
            $table->decimal('luastanahkasdesa', 15, 2)->nullable();
            $table->string('kelastanahkasdesa')->nullable();
            $table->date('tanggaltanahkasdesa')->nullable();

            $table->integer('kdperolehantkd')->nullable();
            $table->integer('kdjenistkd')->nullable();
            $table->integer('kdpatok')->nullable();
            $table->integer('kdpapannama')->nullable();

            $table->string('lokasitanahkasdesa')->nullable();
            $table->string('peruntukantanahkasdesa')->nullable();
            $table->string('mutasitanahkasdesa')->nullable();
            $table->text('keterangantanahkasdesa')->nullable();

            $table->string('fototanahkasdesa')->nullable(); // path foto
            $table->string('userinput')->nullable();
            $table->timestamp('inputtime')->useCurrent();

            // Foreign Key
            $table->foreign('kdperolehantkd')->references('kdperolehantkd')->on('master_perolehantkd');
            $table->foreign('kdjenistkd')->references('kdjenistkd')->on('master_jenistkd');
            $table->foreign('kdpatok')->references('kdpatok')->on('master_patok');
            $table->foreign('kdpapannama')->references('kdpapannama')->on('master_papannama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tanah_kas_desas');
    }
};
