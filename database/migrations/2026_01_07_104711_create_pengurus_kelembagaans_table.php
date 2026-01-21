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
            Schema::create('pengurus_kelembagaan', function (Blueprint $table) {
                  $table->bigIncrements('id_pengurus');

                  $table->string('nomor_induk')->unique();
                  $table->string('nama_lengkap');
                  $table->string('jenis_kelamin', 10)->nullable();
                  $table->string('tempat_lahir')->nullable();
                  $table->date('tanggal_lahir')->nullable();
                  $table->text('alamat')->nullable();
                  $table->string('no_hp')->nullable();
                  $table->string('email')->nullable();

                  // ===== RELASI MASTER =====
                  $table->integer('kdjabatan');
                  $table->integer('kdunit');

                  // Periode awal & akhir
                  $table->integer('kdperiode');        // tahun awal
                  $table->integer('kdperiode_akhir');  // tahun akhir

                  $table->integer('kdstatus');
                  $table->integer('kdjenissk');

                  // Data SK
                  $table->string('no_sk', 100);
                  $table->date('tanggal_sk');

                  // File pendukung
                  $table->string('foto')->nullable();
                  $table->string('tanda_tangan')->nullable();

                  $table->text('keterangan')->nullable();
                  $table->timestamps();

                  // ===== FOREIGN KEYS =====
                  $table->foreign('kdjabatan')
                        ->references('kdjabatan')
                        ->on('master_jabatan_kelembagaan');

                  $table->foreign('kdunit')
                        ->references('kdunit')
                        ->on('master_unit_kelembagaan');

                  // ke tabel awal
                  $table->foreign('kdperiode')
                        ->references('kdperiode')
                        ->on('master_periode_kelembagaan');

                  // ke tabel akhir
                  $table->foreign('kdperiode_akhir')
                        ->references('kdperiode')
                        ->on('master_periode_kelembagaan_akhir');

                  $table->foreign('kdstatus')
                        ->references('kdstatus')
                        ->on('master_status_pengurus_kelembagaan');

                  $table->foreign('kdjenissk')
                        ->references('kdjenissk')
                        ->on('master_jenis_sk_kelembagaan');
            });
      }

      public function down(): void
      {
            Schema::dropIfExists('pengurus_kelembagaan');
      }
};
