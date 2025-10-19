<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_kelahiran', function (Blueprint $table) {
            $table->string('nik', 16); // FK to data_penduduk
			
            $table->integer('kdtempatpersalinan')->nullable(); // relasi ke master_tempatpersalinan
            $table->integer('kdjeniskelahiran')->nullable(); // relasi ke master_jeniskelahiran
            $table->integer('kdpertolonganpersalinan')->nullable(); // relasi ke master_pertolonganpersalinan
            $table->time('kelahiran_jamkelahiran')->nullable(); // required
            $table->integer('kelahiran_kelahiranke')->nullable(); // required
            $table->integer('kelahiran_berat')->nullable(); // required
            $table->integer('kelahiran_panjang')->nullable(); // required
            $table->string('kelahiran_nikibu', 16)->nullable(); // FK to data_penduduk (ibu)
            $table->string('kelahiran_nikayah', 16)->nullable(); // FK to data_penduduk (ayah)
            $table->string('kelahiran_rw', 3)->nullable();
            $table->string('kelahiran_rt', 3)->nullable();

            $table->integer('kdprovinsi')->nullable();
            $table->integer('kdkabupaten')->nullable();
            $table->integer('kdkecamatan')->nullable();
            $table->integer('kddesa')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');

            // Foreign keys
            $table->foreign('nik')->references('nik')->on('data_penduduk')->onDelete('cascade');
            $table->foreign('kdtempatpersalinan')->references('kdtempatpersalinan')->on('master_tempatpersalinan')->onDelete('set null');
            $table->foreign('kdjeniskelahiran')->references('kdjeniskelahiran')->on('master_jeniskelahiran')->onDelete('set null');
            $table->foreign('kdpertolonganpersalinan')->references('kdpertolonganpersalinan')->on('master_pertolonganpersalinan')->onDelete('set null');
            $table->foreign('kelahiran_nikibu')->references('nik')->on('data_penduduk')->onDelete('set null');
            $table->foreign('kelahiran_nikayah')->references('nik')->on('data_penduduk')->onDelete('set null');
            $table->foreign('kdprovinsi')->references('kdprovinsi')->on('master_provinsi')->onDelete('set null');
            $table->foreign('kdkabupaten')->references('kdkabupaten')->on('master_kabupaten')->onDelete('set null');
            $table->foreign('kdkecamatan')->references('kdkecamatan')->on('master_kecamatan')->onDelete('set null');
            $table->foreign('kddesa')->references('kddesa')->on('master_desa')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_kelahiran');
    }
};
