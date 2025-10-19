<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_programserta', function (Blueprint $table) {
            $table->string('nik', 16); // FK ke data_penduduk

            // 8 kolom programserta (hasil jawaban, 0/1)
            for ($i = 1; $i <= 8; $i++) {
                $table->tinyInteger("programserta_$i")->nullable()->default(0);
            }

            // relasi foreign key
            $table->foreign('nik')->references('nik')->on('data_penduduk')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_programserta');
    }
};