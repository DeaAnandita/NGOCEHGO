<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_sarpraskerja', function (Blueprint $table) {
            $table->string('no_kk', 16); // FK ke data keluarga

            // 25 kolom sarpraskerja (kode jawaban, bisa null)
            for ($i = 1; $i <= 25; $i++) {
                $table->tinyInteger("sarpraskerja_$i")->nullable();
            }

            // relasi foreign key
            $table->foreign('no_kk')
                  ->references('no_kk')
                  ->on('data_keluarga')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_sarpraskerja');
    }
};
