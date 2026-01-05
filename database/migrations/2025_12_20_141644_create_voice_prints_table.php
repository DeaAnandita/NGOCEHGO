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
        Schema::create('voice_prints', function (Blueprint $table) {
            $table->id();
            
            // Referensi ke penduduk (NIK sebagai identifier utama voice print)
            $table->string('nik', 16);
            $table->foreign('nik')
                  ->references('nik')
                  ->on('data_penduduk')
                  ->onDelete('cascade');

            // Referensi ke keluarga (no_kk) - opsional, untuk grouping atau pencarian cepat
            $table->string('no_kk', 16)->nullable();
            $table->foreign('no_kk')
                  ->references('no_kk')
                  ->on('data_keluarga')
                  ->onDelete('set null'); // jika keluarga dihapus, voice print tetap ada

            // Embedding suara (vector dari model ML, disimpan sebagai JSON)
            $table->json('embedding');

            // Opsional: nama file audio asli jika disimpan di storage
            $table->string('filename')->nullable();

            // Timestamp
            $table->timestamps();

            // Index tambahan untuk pencarian cepat
            $table->index('nik');        // paling sering dipakai
            $table->index('no_kk');      // jika sering query per keluarga

            // Optional: pastikan satu penduduk hanya punya satu voice fingerprint
            // $table->unique('nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voice_prints');
    }
};