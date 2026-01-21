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
        Schema::create('buku_kader', function (Blueprint $table) {
            $table->bigIncrements('reg');
            $table->integer('kdkader');
            $table->date('kader_tanggal');
            $table->string('kdpenduduk');
            $table->integer('kdpendidikan');
            $table->integer('kdbidang');
            $table->text('kader_keterangan')->nullable();
            $table->integer('kdstatuskader');
            $table->string('userinput');
            $table->timestamp('inputtime')->useCurrent();

            $table->foreign('kdpendidikan')->references('kdpendidikan')->on('master_pendidikan');
            $table->foreign('kdbidang')->references('kdbidang')->on('master_kader_bidang');
            $table->foreign('kdstatuskader')->references('kdstatuskader')->on('master_status_kader');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_kader');
    }
};
