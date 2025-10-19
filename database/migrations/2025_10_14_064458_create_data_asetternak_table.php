<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_asetternak', function (Blueprint $table) {
            $table->string('no_kk', 16); // FK ke data keluarga

            // 40 kolom aseternak (hasil jawaban, 0/1)
            for ($i = 1; $i <= 40; $i++) {
                $table->tinyInteger("asetternak_$i")->nullable()->default(0);
            }

            // relasi foreign key
            $table->foreign('no_kk')->references('no_kk')->on('data_keluarga')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_asetternak');
    }
};

