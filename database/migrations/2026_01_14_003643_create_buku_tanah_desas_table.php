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
        Schema::create('buku_tanahdesa', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('kdtanahdesa', 30)->unique();
            $table->date('tanggaltanahdesa')->nullable();

            $table->integer('kdjenispemilik')->nullable();
            $table->string('pemiliktanahdesa', 150)->nullable();
            $table->string('kdpemilik', 50)->nullable();

            $table->decimal('luastanahdesa', 12, 2)->nullable();

            $table->integer('kdstatushaktanah')->nullable();
            $table->integer('kdpenggunaantanah')->nullable();
            $table->integer('kdmutasitanah')->nullable();

            $table->date('tanggalmutasitanahdesa')->nullable();
            $table->text('keterangantanahdesa')->nullable();

            $table->string('fototanahdesa')->nullable();

            $table->string('userinput', 50)->nullable();
            $table->timestamp('inputtime')->useCurrent();

            $table->foreign('kdstatushaktanah')->references('kdstatushaktanah')->on('master_statushak_tanah');
            $table->foreign('kdpenggunaantanah')->references('kdpenggunaantanah')->on('master_penggunaan_tanah');
            $table->foreign('kdmutasitanah')->references('kdmutasitanah')->on('master_mutasi_tanah');
            $table->foreign('kdjenispemilik')->references('kdjenispemilik')->on('master_jenispemilik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tanah_desas');
    }
};
