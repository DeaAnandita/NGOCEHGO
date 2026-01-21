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
        Schema::create('buku_bantuan', function (Blueprint $table) {
            $table->bigIncrements('reg');
            $table->integer('kdsasaran');
            $table->integer('kdbantuan');
            $table->string('bantuan_nama');
            $table->date('bantuan_awal');
            $table->date('bantuan_akhir')->nullable();
            $table->decimal('bantuan_jumlah', 15, 2);
            $table->text('bantuan_keterangan')->nullable();
            $table->integer('kdsumber'); // relasi ke master_sumber_dana
            $table->string('userinput');
            $table->timestamp('inputtime')->useCurrent();

            $table->foreign('kdsasaran')->references('kdsasaran')->on('master_sasaran');
            $table->foreign('kdbantuan')->references('kdbantuan')->on('master_bantuan');
            $table->foreign('kdsumber')->references('kdsumber')->on('master_sumber_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_bantuan');
    }
};
