<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_asetkeluarga', function (Blueprint $table) {
            $table->string('no_kk', 16); // FK ke data_keluarga

            // 42 kolom aset keluarga (kode jawaban)
            for ($i = 1; $i <= 42; $i++) {
                $table->tinyInteger("asetkeluarga_$i")->nullable()->default(0);
            }

            // relasi foreign key
            $table->foreign('no_kk')->references('no_kk')->on('data_keluarga')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_asetkeluarga');
    }
};
