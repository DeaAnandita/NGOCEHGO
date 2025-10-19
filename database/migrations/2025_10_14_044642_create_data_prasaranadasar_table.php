<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_prasaranadasar', function (Blueprint $table) {
            $table->string('no_kk', 16); // FK ke data_keluarga

            // Semua kolom relasi ke master tabel masing-masing
            $table->integer('kdstatuspemilikbangunan')->nullable();
            $table->integer('kdstatuspemiliklahan')->nullable();
            $table->integer('kdjenisfisikbangunan')->nullable();
            $table->integer('kdjenislantaibangunan')->nullable();
            $table->integer('kdkondisilantaibangunan')->nullable();
            $table->integer('kdjenisdindingbangunan')->nullable();
            $table->integer('kdkondisidindingbangunan')->nullable();
            $table->integer('kdjenisatapbangunan')->nullable();
            $table->integer('kdkondisiatapbangunan')->nullable();
            $table->integer('kdsumberairminum')->nullable();
            $table->integer('kdkondisisumberair')->nullable();
            $table->integer('kdcaraperolehanair')->nullable();
            $table->integer('kdsumberpeneranganutama')->nullable();
            $table->integer('kdsumberdayaterpasang')->nullable();
            $table->integer('kdbahanbakarmemasak')->nullable();
            $table->integer('kdfasilitastempatbab')->nullable();
            $table->integer('kdpembuanganakhirtinja')->nullable();
            $table->integer('kdcarapembuangansampah')->nullable();
            $table->integer('kdmanfaatmataair')->nullable();

            // Field form tambahan
            $table->float('prasdas_luaslantai')->nullable();
            $table->integer('prasdas_jumlahkamar')->nullable();

            // Relasi ke data_keluarga
            $table->foreign('no_kk')
                ->references('no_kk')
                ->on('data_keluarga')
                ->onDelete('cascade');

            // Relasi ke masing-masing master tabel
            $table->foreign('kdstatuspemilikbangunan')->references('kdstatuspemilikbangunan')->on('master_statuspemilikbangunan')->onDelete('set null');
            $table->foreign('kdstatuspemiliklahan')->references('kdstatuspemiliklahan')->on('master_statuspemiliklahan')->onDelete('set null');
            $table->foreign('kdjenisfisikbangunan')->references('kdjenisfisikbangunan')->on('master_jenisfisikbangunan')->onDelete('set null');
            $table->foreign('kdjenislantaibangunan')->references('kdjenislantaibangunan')->on('master_jenislantaibangunan')->onDelete('set null');
            $table->foreign('kdkondisilantaibangunan')->references('kdkondisilantaibangunan')->on('master_kondisilantaibangunan')->onDelete('set null');
            $table->foreign('kdjenisdindingbangunan')->references('kdjenisdindingbangunan')->on('master_jenisdindingbangunan')->onDelete('set null');
            $table->foreign('kdkondisidindingbangunan')->references('kdkondisidindingbangunan')->on('master_kondisidindingbangunan')->onDelete('set null');
            $table->foreign('kdjenisatapbangunan')->references('kdjenisatapbangunan')->on('master_jenisatapbangunan')->onDelete('set null');
            $table->foreign('kdkondisiatapbangunan')->references('kdkondisiatapbangunan')->on('master_kondisiatapbangunan')->onDelete('set null');
            $table->foreign('kdsumberairminum')->references('kdsumberairminum')->on('master_sumberairminum')->onDelete('set null');
            $table->foreign('kdkondisisumberair')->references('kdkondisisumberair')->on('master_kondisisumberair')->onDelete('set null');
            $table->foreign('kdcaraperolehanair')->references('kdcaraperolehanair')->on('master_caraperolehanair')->onDelete('set null');
            $table->foreign('kdsumberpeneranganutama')->references('kdsumberpeneranganutama')->on('master_sumberpeneranganutama')->onDelete('set null');
            $table->foreign('kdsumberdayaterpasang')->references('kdsumberdayaterpasang')->on('master_sumberdayaterpasang')->onDelete('set null');
            $table->foreign('kdbahanbakarmemasak')->references('kdbahanbakarmemasak')->on('master_bahanbakarmemasak')->onDelete('set null');
            $table->foreign('kdfasilitastempatbab')->references('kdfasilitastempatbab')->on('master_fasilitastempatbab')->onDelete('set null');
            $table->foreign('kdpembuanganakhirtinja')->references('kdpembuanganakhirtinja')->on('master_pembuanganakhirtinja')->onDelete('set null');
            $table->foreign('kdcarapembuangansampah')->references('kdcarapembuangansampah')->on('master_carapembuangansampah')->onDelete('set null');
            $table->foreign('kdmanfaatmataair')->references('kdmanfaatmataair')->on('master_manfaatmataair')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_prasaranadasar');
    }
};
