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
        Schema::create('surats', function (Blueprint $table) {
            $table->bigIncrements('id');

            // PEMILIK PENGAJUAN
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('nik', 20);
            $table->string('nama', 100);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('kewarganegaraan', 50);
            $table->string('agama', 50);
            $table->string('pekerjaan', 100);
            $table->text('alamat');

            $table->string('nomor_surat', 100)->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->text('keperluan');
            $table->text('keterangan_lain')->nullable();

            $table->enum('status', ['menunggu', 'disetujui', 'dicetak', 'ditolak'])->default('menunggu');

            $table->string('cetak_token', 100)->nullable()->unique();
            $table->string('barcode_cetak_path')->nullable();

            $table->string('kode_verifikasi', 100)->nullable()->unique();
            $table->string('barcode_verifikasi_path')->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('printed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
