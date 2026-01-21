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
        Schema::create('buku_inventaris', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->string('kdinventaris')->primary();

            $table->date('inventaris_tanggal')->nullable();

            $table->integer('kdpengguna')->nullable();
            $table->string('anak')->nullable();

            $table->integer('inventaris_volume')->default(0);
            $table->boolean('inventaris_hapus')->default(false);

            $table->integer('kdsatuanbarang')->nullable();
            $table->string('inventaris_identitas')->nullable();

            $table->integer('kdasalbarang')->nullable();
            $table->string('barangasal')->nullable();

            $table->decimal('inventaris_harga', 15, 2)->nullable();

            $table->text('inventaris_keterangan')->nullable();
            $table->string('inventaris_foto')->nullable();

            $table->string('userinput')->nullable();
            $table->timestamp('inputtime')->useCurrent();

            // ================= RELASI =================
            $table->foreign('kdpengguna')
                ->references('kdpengguna')
                ->on('master_pengguna');

            $table->foreign('kdsatuanbarang')
                ->references('kdsatuanbarang')
                ->on('master_satuanbarang');

            $table->foreign('kdasalbarang')
                ->references('kdasalbarang')
                ->on('master_asalbarang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_inventaris');
    }
};
