<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_lembagadesa', function (Blueprint $table) {
            $table->string('nik', 16); // FK ke data_penduduk

            // 9 kolom lemdes (hasil jawaban, 0/1)
            for ($i = 1; $i <= 9; $i++) {
                $table->tinyInteger("lemdes_$i")->nullable()->default(0);
            }

            // relasi foreign key
            $table->foreign('nik')->references('nik')->on('data_penduduk')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_lembagadesa');
    }
};