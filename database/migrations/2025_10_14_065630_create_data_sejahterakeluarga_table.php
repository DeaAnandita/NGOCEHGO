<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_sejahterakeluarga', function (Blueprint $table) {
            $table->string('no_kk', 16); // FK ke data keluarga

            // 8 kolom sejahterakeluarga (hasil jawaban, 0/1)
            for ($i = 61; $i <= 68; $i++) {
                $table->string("sejahterakeluarga_$i")->nullable()->default(0);
            }

            // relasi foreign key
            $table->foreign('no_kk')->references('no_kk')->on('data_keluarga')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_sejahterakeluarga');
    }
};