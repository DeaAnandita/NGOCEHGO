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
        Schema::create('buku_proyek', function (Blueprint $table) {
            $table->bigIncrements('reg');
            $table->integer('kdproyek');
            $table->date('proyek_tanggal');
            $table->integer('kdkegiatan');
            $table->integer('kdpelaksana');
            $table->integer('kdlokasi');
            $table->decimal('proyek_nominal', 15, 2);
            $table->text('proyek_manfaat')->nullable();
            $table->text('proyek_keterangan')->nullable();
            $table->integer('kdsumber');
            $table->string('userinput');
            $table->timestamp('inputtime')->useCurrent();

            $table->foreign('kdkegiatan')->references('kdkegiatan')->on('master_kegiatan');
            $table->foreign('kdpelaksana')->references('kdpelaksana')->on('master_pelaksana');
            $table->foreign('kdlokasi')->references('kdlokasi')->on('master_lokasi');
            $table->foreign('kdsumber')->references('kdsumber')->on('master_sumber_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_proyeks');
    }
};
