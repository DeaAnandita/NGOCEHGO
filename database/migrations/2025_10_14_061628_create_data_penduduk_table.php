<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('data_penduduk', function (Blueprint $table) {
            $table->string('nik', 16)->primary(); // PK, unique, required
            $table->string('no_kk', 16); // FK ke data_keluarga

            // Relasi dan data dasar
            $table->integer('kdmutasimasuk')->nullable(); // relasi ke master_mutasimasuk
            $table->date('penduduk_tanggalmutasi')->default(DB::raw('CURRENT_DATE'));
            $table->string('penduduk_kewarganegaraan')->default('INDONESIA');
            $table->string('penduduk_nourutkk', 4); // required
            $table->string('penduduk_goldarah'); // required
            $table->string('penduduk_noaktalahir'); // required
            $table->string('penduduk_namalengkap'); // required
            $table->string('penduduk_tempatlahir'); // required
            $table->date('penduduk_tanggallahir'); // required

            // Relasi ke master tabel
            $table->integer('kdjeniskelamin')->nullable(); //relasi ke master_jeniskelamin
            $table->integer('kdagama')->nullable(); //relasi ke master_agama
            $table->integer('kdhubungankeluarga')->nullable(); //relasi ke master_hubungankeluarga
            $table->integer('kdhubungankepalakeluarga')->nullable(); //relasi ke master_hubungankepalakeluarga
            $table->integer('kdstatuskawin')->nullable(); //relasi ke master_statuskawin
            $table->integer('kdaktanikah')->nullable(); //relasi ke master_aktanikah
            $table->integer('kdtercantumdalamkk')->nullable(); //relasi ke master_tercantumdalamkk
            $table->integer('kdstatustinggal')->nullable(); //relasi ke master_statustinggal
            $table->integer('kdkartuidentitas')->nullable(); //relasi ke master_kartuidentitas
            $table->integer('kdpekerjaan')->nullable(); // relasi ke master_pekerjaan

            // Informasi tambahan
            $table->string('penduduk_namaayah')->nullable();
            $table->string('penduduk_namaibu')->nullable();
            $table->string('penduduk_namatempatbekerja')->nullable();

            // Wilayah datang (jika mutasi datang)
            $table->integer('kdprovinsi')->nullable();
            $table->bigInteger('kdkabupaten')->nullable();
            $table->bigInteger('kdkecamatan')->nullable();
            $table->bigInteger('kddesa')->nullable();
            
            // Foreign key
            $table->foreign('no_kk')->references('no_kk')->on('data_keluarga')->onDelete('cascade');
            $table->foreign('kdmutasimasuk')->references('kdmutasimasuk')->on('master_mutasimasuk')->onDelete('set null');
            $table->foreign('kdjeniskelamin')->references('kdjeniskelamin')->on('master_jeniskelamin')->onDelete('set null');
            $table->foreign('kdagama')->references('kdagama')->on('master_agama')->onDelete('set null');
            $table->foreign('kdhubungankeluarga')->references('kdhubungankeluarga')->on('master_hubungankeluarga')->onDelete('set null');
            $table->foreign('kdhubungankepalakeluarga')->references('kdhubungankepalakeluarga')->on('master_hubungankepalakeluarga')->onDelete('set null');
            $table->foreign('kdstatuskawin')->references('kdstatuskawin')->on('master_statuskawin')->onDelete('set null');
            $table->foreign('kdaktanikah')->references('kdaktanikah')->on('master_aktanikah')->onDelete('set null');
            $table->foreign('kdtercantumdalamkk')->references('kdtercantumdalamkk')->on('master_tercantumdalamkk')->onDelete('set null');
            $table->foreign('kdstatustinggal')->references('kdstatustinggal')->on('master_statustinggal')->onDelete('set null');
            $table->foreign('kdkartuidentitas')->references('kdkartuidentitas')->on('master_kartuidentitas')->onDelete('set null');
            $table->foreign('kdpekerjaan')->references('kdpekerjaan')->on('master_pekerjaan')->onDelete('set null');

            $table->foreign('kdprovinsi')->references('kdprovinsi')->on('master_provinsi')->onDelete('set null');
            $table->foreign('kdkabupaten')->references('kdkabupaten')->on('master_kabupaten')->onDelete('set null');
            $table->foreign('kdkecamatan')->references('kdkecamatan')->on('master_kecamatan')->onDelete('set null');
            $table->foreign('kddesa')->references('kddesa')->on('master_desa')->onDelete('set null');
        });
    }

    /**
     * Hapus tabel.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_penduduk');
    }
};