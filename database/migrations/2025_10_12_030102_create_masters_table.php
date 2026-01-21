<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // master_asetkeluarga
        Schema::create('master_asetkeluarga', function (Blueprint $table) {
            $table->integer('kdasetkeluarga')->primary();
            $table->string('asetkeluarga');
        });
        // master_asetlahan
        Schema::create('master_asetlahan', function (Blueprint $table) {
            $table->integer('kdasetlahan')->primary();
            $table->string('asetlahan');
        });
        // master_asetternak
        Schema::create('master_asetternak', function (Blueprint $table) {
            $table->integer('kdasetternak')->primary();
            $table->string('asetternak');
        });
        // master_asetperikanan
        Schema::create('master_asetperikanan', function (Blueprint $table) {
            $table->integer('kdasetperikanan')->primary();
            $table->string('asetperikanan');
        });
        // master_sarpraskerja
        Schema::create('master_sarpraskerja', function (Blueprint $table) {
            $table->integer('kdsarpraskerja')->primary();
            $table->string('sarpraskerja');
        });
        // master_typejawab
        Schema::create('master_typejawab', function (Blueprint $table) {
            $table->integer('kdtypejawab')->primary();
            $table->string('typejawab');
        });
        // master_pembangunankeluarga
        Schema::create('master_pembangunankeluarga', function (Blueprint $table) {
            $table->integer('kdpembangunankeluarga')->primary();
            $table->string('pembangunankeluarga');
            $table->integer('kdtypejawab'); // FK ke master_typejawab
            $table->foreign('kdtypejawab')->references('kdtypejawab')->on('master_typejawab')->onDelete('cascade');
        });
        // master_konfliksosial
        Schema::create('master_konfliksosial', function (Blueprint $table) {
            $table->integer('kdkonfliksosial')->primary();
            $table->string('konfliksosial');
        });
        // master_kualitasibuhamil
        Schema::create('master_kualitasibuhamil', function (Blueprint $table) {
            $table->integer('kdkualitasibuhamil')->primary();
            $table->string('kualitasibuhamil');
        });
        // master_kualitasbayi
        Schema::create('master_kualitasbayi', function (Blueprint $table) {
            $table->integer('kdkualitasbayi')->primary();
            $table->string('kualitasbayi');
        });
        // master_programserta
        Schema::create('master_programserta', function (Blueprint $table) {
            $table->integer('kdprogramserta')->primary();
            $table->string('programserta');
        });
        // master_jenislembaga
        Schema::create('master_jenislembaga', function (Blueprint $table) {
            $table->integer('kdjenislembaga')->primary();
            $table->string('jenislembaga');
        });
        // master_lembaga
        Schema::create('master_lembaga', function (Blueprint $table) {
            $table->integer('kdlembaga')->primary();
            $table->integer('kdjenislembaga'); // FK ke master_jenislembaga
            $table->string('lembaga');
            $table->foreign('kdjenislembaga')->references('kdjenislembaga')->on('master_jenislembaga')->onDelete('cascade');
        });
        // master_agama
        Schema::create('master_agama', function (Blueprint $table) {
            $table->integer('kdagama')->primary();
            $table->string('agama');
        });
        // master_aktanikah
        Schema::create('master_aktanikah', function (Blueprint $table) {
            $table->integer('kdaktanikah')->primary();
            $table->string('aktanikah');
        });
        // master_bahanbakarmemasak
        Schema::create('master_bahanbakarmemasak', function (Blueprint $table) {
            $table->integer('kdbahanbakarmemasak')->primary();
            $table->string('bahanbakarmemasak');
        });
        // master_carapembuangansampah
        Schema::create('master_carapembuangansampah', function (Blueprint $table) {
            $table->integer('kdcarapembuangansampah')->primary();
            $table->string('carapembuangansampah');
        });
        // master_caraperolehanair
        Schema::create('master_caraperolehanair', function (Blueprint $table) {
            $table->integer('kdcaraperolehanair')->primary();
            $table->string('caraperolehanair');
        });

        // 1. Provinsi
        Schema::create('master_provinsi', function (Blueprint $table) {
            $table->integer('kdprovinsi')->primary(); // 11-96 → cukup integer
            $table->string('provinsi');
        });

        // 2. Kabupaten/Kota → PAKAI bigInteger
        Schema::create('master_kabupaten', function (Blueprint $table) {
            $table->bigInteger('kdkabupaten')->primary();     // 3301, 3171, 3578, dll
            $table->integer('kdprovinsi');
            $table->string('kabupaten');

            $table->foreign('kdprovinsi')
                ->references('kdprovinsi')
                ->on('master_provinsi')
                ->onDelete('cascade');
        });

        // 3. Kecamatan → PAKAI bigInteger
        Schema::create('master_kecamatan', function (Blueprint $table) {
            $table->bigInteger('kdkecamatan')->primary();     // 330101, 317101, dll
            $table->bigInteger('kdkabupaten');
            $table->string('kecamatan');

            $table->foreign('kdkabupaten')
                ->references('kdkabupaten')
                ->on('master_kabupaten')
                ->onDelete('cascade');
        });

        // 4. Desa/Kelurahan → PAKAI bigInteger
        Schema::create('master_desa', function (Blueprint $table) {
            $table->bigInteger('kddesa')->primary();          // 3301020001, 3171010001, dll
            $table->bigInteger('kdkecamatan');
            $table->string('desa');

            $table->foreign('kdkecamatan')
                ->references('kdkecamatan')
                ->on('master_kecamatan')
                ->onDelete('cascade');
        });

        // master_fasilitastempatbab
        Schema::create('master_fasilitastempatbab', function (Blueprint $table) {
            $table->integer('kdfasilitastempatbab')->primary();
            $table->string('fasilitastempatbab');
        });
        // master_hubungankeluarga
        Schema::create('master_hubungankeluarga', function (Blueprint $table) {
            $table->integer('kdhubungankeluarga')->primary();
            $table->string('hubungankeluarga');
        });
        // master_hubungankepalakeluarga
        Schema::create('master_hubungankepalakeluarga', function (Blueprint $table) {
            $table->integer('kdhubungankepalakeluarga')->primary();
            $table->string('hubungankepalakeluarga');
        });
        // master_ijasahterakhir
        Schema::create('master_ijasahterakhir', function (Blueprint $table) {
            $table->integer('kdijasahterakhir')->primary();
            $table->string('ijasahterakhir');
        });
        // master_imunisasi
        Schema::create('master_imunisasi', function (Blueprint $table) {
            $table->integer('kdimunisasi')->primary();
            $table->string('imunisasi');
        });
        // master_jabatan
        Schema::create('master_jabatan', function (Blueprint $table) {
            $table->integer('kdjabatan')->primary();
            $table->string('jabatan');
        });
        // master_jenisatapbangunan
        Schema::create('master_jenisatapbangunan', function (Blueprint $table) {
            $table->integer('kdjenisatapbangunan')->primary();
            $table->string('jenisatapbangunan');
        });
        // master_jenisbahangalian
        Schema::create('master_jenisbahangalian', function (Blueprint $table) {
            $table->integer('kdjenisbahangalian')->primary();
            $table->string('jenisbahangalian');
        });
        // master_jenisdindingbangunan
        Schema::create('master_jenisdindingbangunan', function (Blueprint $table) {
            $table->integer('kdjenisdindingbangunan')->primary();
            $table->string('jenisdindingbangunan');
        });
        // master_jenisdisabilitas
        Schema::create('master_jenisdisabilitas', function (Blueprint $table) {
            $table->integer('kdjenisdisabilitas')->primary();
            $table->string('jenisdisabilitas');
        });
        //master_jenisfisikbangunan
        Schema::create('master_jenisfisikbangunan', function (Blueprint $table) {
            $table->integer('kdjenisfisikbangunan')->primary();
            $table->string('jenisfisikbangunan');
        });
        // master_jeniskelahiran
        Schema::create('master_jeniskelahiran', function (Blueprint $table) {
            $table->integer('kdjeniskelahiran')->primary();
            $table->string('jeniskelahiran');
        });
        // master_jeniskelamin
        Schema::create('master_jeniskelamin', function (Blueprint $table) {
            $table->integer('kdjeniskelamin')->primary();
            $table->string('jeniskelamin');
        });
        // master_jenislantaibangunan
        Schema::create('master_jenislantaibangunan', function (Blueprint $table) {
            $table->integer('kdjenislantaibangunan')->primary();
            $table->string('jenislantaibangunan');
        });
        // master_kartuidentitas
        Schema::create('master_kartuidentitas', function (Blueprint $table) {
            $table->integer('kdkartuidentitas')->primary();
            $table->string('kartuidentitas');
        });
        // master_kondisiatapbangunan
        Schema::create('master_kondisiatapbangunan', function (Blueprint $table) {
            $table->integer('kdkondisiatapbangunan')->primary();
            $table->string('kondisiatapbangunan');
        });
        // master_kondisidindingbangunan
        Schema::create('master_kondisidindingbangunan', function (Blueprint $table) {
            $table->integer('kdkondisidindingbangunan')->primary();
            $table->string('kondisidindingbangunan');
        });
        // master_kondisilantaibangunan
        Schema::create('master_kondisilantaibangunan', function (Blueprint $table) {
            $table->integer('kdkondisilantaibangunan')->primary();
            $table->string('kondisilantaibangunan');
        });
        // master_kondisisumberair
        Schema::create('master_kondisisumberair', function (Blueprint $table) {
            $table->integer('kdkondisisumberair')->primary();
            $table->string('kondisisumberair');
        });
        // master_kondisilapanganusaha
        Schema::create('master_kondisilapanganusaha', function (Blueprint $table) {
            $table->integer('kdkondisilapanganusaha')->primary();
            $table->string('kondisilapanganusaha');
        });
        // master_manfaatmataair
        Schema::create('master_manfaatmataair', function (Blueprint $table) {
            $table->integer('kdmanfaatmataair')->primary();
            $table->string('manfaatmataair');
        });
        // master_mutasikeluar
        Schema::create('master_mutasikeluar', function (Blueprint $table) {
            $table->integer('kdmutasikeluar')->primary();
            $table->string('mutasikeluar');
        });
        // master_mutasimasuk
        Schema::create('master_mutasimasuk', function (Blueprint $table) {
            $table->integer('kdmutasimasuk')->primary();
            $table->string('mutasimasuk');
        });
        // master_omsetusaha
        Schema::create('master_omsetusaha', function (Blueprint $table) {
            $table->integer('kdomsetusaha')->primary();
            $table->string('omsetusaha');
        });
        // master_partisipasisekolah
        Schema::create('master_partisipasisekolah', function (Blueprint $table) {
            $table->integer('kdpartisipasisekolah')->primary();
            $table->string('partisipasisekolah');
        });
        // master_pekerjaan
        Schema::create('master_pekerjaan', function (Blueprint $table) {
            $table->integer('kdpekerjaan')->primary();
            $table->string('pekerjaan');
        });
        // master_pembuanganakhirtinja
        Schema::create('master_pembuanganakhirtinja', function (Blueprint $table) {
            $table->integer('kdpembuanganakhirtinja')->primary();
            $table->string('pembuanganakhirtinja');
        });
        // master_pendapatanperbulan
        Schema::create('master_pendapatanperbulan', function (Blueprint $table) {
            $table->integer('kdpendapatanperbulan')->primary();
            $table->string('pendapatanperbulan');
        });
        // master_penyakitkronis
        Schema::create('master_penyakitkronis', function (Blueprint $table) {
            $table->integer('kdpenyakitkronis')->primary();
            $table->string('penyakitkronis');
        });
        // master_pertolonganpersalinan
        Schema::create('master_pertolonganpersalinan', function (Blueprint $table) {
            $table->integer('kdpertolonganpersalinan')->primary();
            $table->string('pertolonganpersalinan');
        });
        // master_statuskawin
        Schema::create('master_statuskawin', function (Blueprint $table) {
            $table->integer('kdstatuskawin')->primary();
            $table->string('statuskawin');
        });
        // master_statuskedudukankerja
        Schema::create('master_statuskedudukankerja', function (Blueprint $table) {
            $table->integer('kdstatuskedudukankerja')->primary();
            $table->string('statuskedudukankerja');
        });
        // master_statuspemilikbangunan
        Schema::create('master_statuspemilikbangunan', function (Blueprint $table) {
            $table->integer('kdstatuspemilikbangunan')->primary();
            $table->string('statuspemilikbangunan');
        });
        // master_statuspemiliklahan
        Schema::create('master_statuspemiliklahan', function (Blueprint $table) {
            $table->integer('kdstatuspemiliklahan')->primary();
            $table->string('statuspemiliklahan');
        });
        // master_statustinggal
        Schema::create('master_statustinggal', function (Blueprint $table) {
            $table->integer('kdstatustinggal')->primary();
            $table->string('statustinggal');
        });
        // master_sumberairminum
        Schema::create('master_sumberairminum', function (Blueprint $table) {
            $table->integer('kdsumberairminum')->primary();
            $table->string('sumberairminum');
        });
        // master_sumberdayaterpasang
        Schema::create('master_sumberdayaterpasang', function (Blueprint $table) {
            $table->integer('kdsumberdayaterpasang')->primary();
            $table->string('sumberdayaterpasang');
        });
        // master_sumberpeneranganutama
        Schema::create('master_sumberpeneranganutama', function (Blueprint $table) {
            $table->integer('kdsumberpeneranganutama')->primary();
            $table->string('sumberpeneranganutama');
        });
        // master_tempatpersalinan
        Schema::create('master_tempatpersalinan', function (Blueprint $table) {
            $table->integer('kdtempatpersalinan')->primary();
            $table->string('tempatpersalinan');
        });
        // master_tempatusaha
        Schema::create('master_tempatusaha', function (Blueprint $table) {
            $table->integer('kdtempatusaha')->primary();
            $table->string('tempatusaha');
        });
        // master_tercantumdalamkk
        Schema::create('master_tercantumdalamkk', function (Blueprint $table) {
            $table->integer('kdtercantumdalamkk')->primary();
            $table->string('tercantumdalamkk');
        });
        // master_tingkatsulitdisabilitas
        Schema::create('master_tingkatsulitdisabilitas', function (Blueprint $table) {
            $table->integer('kdtingkatsulitdisabilitas')->primary();
            $table->string('tingkatsulitdisabilitas');
        });
        // master_jawab
        Schema::create('master_jawab', function (Blueprint $table) {
            $table->integer('kdjawab')->primary();
            $table->string('jawab');
        });
        // master_jawablahan
        Schema::create('master_jawablahan', function (Blueprint $table) {
            $table->integer('kdjawablahan')->primary();
            $table->string('jawablahan');
        });
        // master_jawabbangun
        Schema::create('master_jawabbangun', function (Blueprint $table) {
            $table->integer('kdjawabbangun')->primary();
            $table->string('jawabbangun');
        });
        // master_jawabkonflik
        Schema::create('master_jawabkonflik', function (Blueprint $table) {
            $table->integer('kdjawabkonflik')->primary();
            $table->string('jawabkonflik');
        });
        // master_jawabkualitasbayi
        Schema::create('master_jawabkualitasbayi', function (Blueprint $table) {
            $table->integer('kdjawabkualitasbayi')->primary();
            $table->string('jawabkualitasbayi');
        });
        // master_jawabkualitasibuhamil
        Schema::create('master_jawabkualitasibuhamil', function (Blueprint $table) {
            $table->integer('kdjawabkualitasibuhamil')->primary();
            $table->string('jawabkualitasibuhamil');
        });
        // master_jawablemdes
        Schema::create('master_jawablemdes', function (Blueprint $table) {
            $table->integer('kdjawablemdes')->primary();
            $table->string('jawablemdes');
        });
        // master_jawablemek
        Schema::create('master_jawablemek', function (Blueprint $table) {
            $table->integer('kdjawablemek')->primary();
            $table->string('jawablemek');
        });
        // master_jawablemmas
        Schema::create('master_jawablemmas', function (Blueprint $table) {
            $table->integer('kdjawablemmas')->primary();
            $table->string('jawablemmas');
        });
        // master_jawabprogramserta
        Schema::create('master_jawabprogramserta', function (Blueprint $table) {
            $table->integer('kdjawabprogramserta')->primary();
            $table->string('jawabprogramserta');
        });
        // master_jawabsarpras
        Schema::create('master_jawabsarpras', function (Blueprint $table) {
            $table->integer('kdjawabsarpras')->primary();
            $table->string('jawabsarpras');
        });
        // master_jawabtempatpersalinan
        Schema::create('master_jawabtempatpersalinan', function (Blueprint $table) {
            $table->integer('kdjawabtempatpersalinan')->primary();
            $table->string('jawabtempatpersalinan');
        });
        // master_dusun
        Schema::create('master_dusun', function (Blueprint $table) {
            $table->integer('kddusun')->primary();
            $table->string('dusun');
        });
        // master_inventaris
        Schema::create('master_inventaris', function (Blueprint $table) {
            $table->integer('kdinventaris')->primary();
            $table->string('inventaris');
        });
        Schema::create('master_jabatan_kelembagaan', function (Blueprint $table) {
            $table->integer('kdjabatan')->primary();
            $table->string('jabatan');
        });

        // MASTER UNIT / BIDANG KELEMBAGAAN
        Schema::create('master_unit_kelembagaan', function (Blueprint $table) {
            $table->integer('kdunit')->primary();
            $table->string('nama_unit');
        });

        // MASTER PERIODE KELEMBAGAAN
        Schema::create('master_periode_kelembagaan', function (Blueprint $table) {
            $table->integer('kdperiode')->primary();
            $table->string('tahun_awal');
        });
        Schema::create('master_periode_kelembagaan_akhir', function (Blueprint $table) {
            $table->integer('kdperiode')->primary();   // ini = tahun awal
            $table->string('akhir');                  // tahun akhir
        });

        // MASTER STATUS PENGURUS KELEMBAGAAN
        Schema::create('master_status_pengurus_kelembagaan', function (Blueprint $table) {
            $table->integer('kdstatus')->primary();
            $table->string('status_pengurus');
        });

        // MASTER JENIS SK KELEMBAGAAN
        Schema::create('master_jenis_sk_kelembagaan', function (Blueprint $table) {
            $table->integer('kdjenissk')->primary();
            $table->string('jenis_sk');
        });
        Schema::create('master_jenis_keputusan', function (Blueprint $table) {
            $table->integer('kdjenis')->primary();
            $table->string('jenis_keputusan');
        });
        Schema::create('master_kriteria_keputusan', function (Blueprint $table) {
            $table->integer('kdkriteria')->primary();
            $table->string('kriteria');
        });

        Schema::create('master_metode_keputusan', function (Blueprint $table) {
            $table->integer('kdmetode')->primary();
            $table->string('metode');
        });
        Schema::create('master_status_keputusan', function (Blueprint $table) {
            $table->integer('kdstatus')->primary();
            $table->string('status_keputusan');
        });
        Schema::create('master_unit_keputusan', function (Blueprint $table) {
            $table->integer('kdunit')->primary();
            $table->string('unit_keputusan');
        });
        Schema::create('master_jenis_kegiatan', function (Blueprint $table) {
            $table->integer('kdjenis')->primary();
            $table->string('jenis_kegiatan');
        });
        Schema::create('master_sumber_dana', function (Blueprint $table) {
            $table->integer('kdsumber')->primary();
            $table->string('sumber_dana');
        });
        Schema::create('master_status_kegiatan', function (Blueprint $table) {
            $table->integer('kdstatus')->primary();
            $table->string('status_kegiatan');
        });
        Schema::create('master_jenis_agenda', function (Blueprint $table) {
            $table->integer('kdjenis')->primary();
            $table->string('jenis_agenda');
        });
        Schema::create('master_status_agenda', function (Blueprint $table) {
            $table->integer('kdstatus')->primary();
            $table->string('status_agenda');
        });
        Schema::create('master_tempat_agenda', function (Blueprint $table) {
            $table->integer('kdtempat')->primary();
            $table->string('tempat_agenda');
        });
        Schema::create('master_status_anggaran', function (Blueprint $table) {
            $table->integer('kdstatus')->primary();
            $table->string('status_anggaran');
        });
        Schema::create('master_status_pencairan', function (Blueprint $table) {
            $table->integer('kdstatus')->primary();
            $table->string('status_pencairan');
        });
        Schema::create('master_status_lpj', function (Blueprint $table) {
            $table->integer('kdstatus')->primary();
            $table->string('status_lpj');
        });
        Schema::create('master_jenisperaturandesa', function (Blueprint $table) {
            $table->integer('kdjenisperaturandesa')->primary();
            $table->string('jenisperaturandesa');
        });
        // master_asalbarang
        Schema::create('master_asalbarang', function (Blueprint $table) {
            $table->integer('kdasalbarang')->primary();
            $table->string('asalbarang');
        });

        // master_pengguna
        Schema::create('master_pengguna', function (Blueprint $table) {
            $table->integer('kdpengguna')->primary();
            $table->string('kodepengguna')->nullable();
            $table->string('pengguna');
        });

        // master_satuanbarang
        Schema::create('master_satuanbarang', function (Blueprint $table) {
            $table->integer('kdsatuanbarang')->primary();
            $table->string('satuanbarang');
        });

        // master_aparat
        Schema::create('master_aparat', function (Blueprint $table) {
            $table->integer('kdaparat')->primary();
            $table->string('aparat');
        });

        // master_perolehantkd
        Schema::create('master_perolehantkd', function (Blueprint $table) {
            $table->integer('kdperolehantkd')->primary();
            $table->string('perolehantkd');
        });

        // master_jenistkd
        Schema::create('master_jenistkd', function (Blueprint $table) {
            $table->integer('kdjenistkd')->primary();
            $table->string('jenistkd');
        });

        // master_patok
        Schema::create('master_patok', function (Blueprint $table) {
            $table->integer('kdpatok')->primary();
            $table->string('patok');
        });

        // master_papannama
        Schema::create('master_papannama', function (Blueprint $table) {
            $table->integer('kdpapannama')->primary();
            $table->string('papannama');
        });

        // master_jenisagenda
        Schema::create('master_jenisagenda_umum', function (Blueprint $table) {
            $table->integer('kdjenisagenda_umum')->primary();
            $table->string('jenisagenda_umum');
        });

        Schema::create('master_jeniskeputusan_umum', function (Blueprint $table) {
            $table->integer('kdjeniskeputusan_umum')->primary();
            $table->string('jeniskeputusan_umum');
        });

        Schema::create('master_statushak_tanah', function (Blueprint $table) {
            $table->integer('kdstatushaktanah')->primary();
            $table->string('statushaktanah');
        });

        Schema::create('master_penggunaan_tanah', function (Blueprint $table) {
            $table->integer('kdpenggunaantanah')->primary();
            $table->string('penggunaantanah');
        });

        Schema::create('master_mutasi_tanah', function (Blueprint $table) {
            $table->integer('kdmutasitanah')->primary();
            $table->string('mutasitanah');
        });

        Schema::create('master_jenispemilik', function (Blueprint $table) {
            $table->integer('kdjenispemilik')->primary();
            $table->string('jenispemilik');
        });
        Schema::create('master_sasaran', function (Blueprint $table) {
            $table->integer('kdsasaran')->primary();
            $table->string('sasaran');
        });

        Schema::create('master_bantuan', function (Blueprint $table) {
            $table->integer('kdbantuan')->primary();
            $table->string('bantuan');
        });

        Schema::create('master_pendidikan', function (Blueprint $table) {
            $table->integer('kdpendidikan')->primary();
            $table->string('pendidikan');
        });

        Schema::create('master_kader_bidang', function (Blueprint $table) {
            $table->integer('kdbidang')->primary();
            $table->string('bidang');
        });

        Schema::create('master_status_kader', function (Blueprint $table) {
            $table->integer('kdstatuskader')->primary();
            $table->string('statuskader');
        });

        Schema::create('master_kegiatan', function (Blueprint $table) {
            $table->integer('kdkegiatan')->primary();
            $table->string('kegiatan');
        });

        Schema::create('master_lokasi', function (Blueprint $table) {
            $table->integer('kdlokasi')->primary();
            $table->string('lokasi');
        });

        Schema::create('master_pelaksana', function (Blueprint $table) {
            $table->integer('kdpelaksana')->primary();
            $table->string('pelaksana');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_lokasi');
        Schema::dropIfExists('master_pelaksana');
        Schema::dropIfExists('master_kegiatan');
        Schema::dropIfExists('master_status_kader');
        Schema::dropIfExists('master_kader_bidang');
        Schema::dropIfExists('master_pendidikan');
        Schema::dropIfExists('master_sumberdana');
        Schema::dropIfExists('master_bantuan');
        Schema::dropIfExists('master_sasaran');
        Schema::dropIfExists('master_jenispemilik');
        Schema::dropIfExists('master_mutasi_tanah');
        Schema::dropIfExists('master_penggunaan_tanah');
        Schema::dropIfExists('master_statushak_tanah');
        Schema::dropIfExists('master_jeniskeputusan_umum');
        Schema::dropIfExists('master_jenisagenda_umum');
        Schema::dropIfExists('master_papannama');
        Schema::dropIfExists('master_patok');
        Schema::dropIfExists('master_jenistkd');
        Schema::dropIfExists('master_perolehantkd');
        Schema::dropIfExists('master_aparat');
        Schema::dropIfExists('master_satuanbarang');
        Schema::dropIfExists('master_pengguna');
        Schema::dropIfExists('master_asalbarang');
        Schema::dropIfExists('master_jenisperaturandesa');
        Schema::dropIfExists('master_status_lpj');
        Schema::dropIfExists('master_status_pencairan');
        Schema::dropIfExists('master_status_anggaran');
        Schema::dropIfExists('master_tempat_agenda');
        Schema::dropIfExists('master_status_agenda');
        Schema::dropIfExists('master_jenis_agenda');
        Schema::dropIfExists('master_metode_keputusan');
        Schema::dropIfExists('master_kriteria_keputusan');
        Schema::dropIfExists('master_jenis_keputusan');
        Schema::dropIfExists('master_unit_keputusan');
        Schema::dropIfExists('master_status_keputusan');
        Schema::dropIfExists('master_jenis_sk_kelembagaan');
        Schema::dropIfExists('master_status_pengurus_kelembagaan');
        Schema::dropIfExists('master_periode_kelembagaan');
        Schema::dropIfExists('master_periode_kelembagaan_akhir');
        Schema::dropIfExists('master_unit_kelembagaan');
        Schema::dropIfExists('master_jabatan_kelembagaan');
        Schema::dropIfExists('master_asetkeluarga');
        Schema::dropIfExists('master_asetlahan');
        Schema::dropIfExists('master_asetternak');
        Schema::dropIfExists('master_asetperikanan');
        Schema::dropIfExists('master_sarpraskerja');
        Schema::dropIfExists('master_typejawab');
        Schema::dropIfExists('master_pembangunankeluarga');
        Schema::dropIfExists('master_konfliksosial');
        Schema::dropIfExists('master_kualitasibuhamil');
        Schema::dropIfExists('master_kualitasbayi');
        Schema::dropIfExists('master_programserta');
        Schema::dropIfExists('master_jenislembaga');
        Schema::dropIfExists('master_lembaga');
        Schema::dropIfExists('master_agama');
        Schema::dropIfExists('master_aktanikah');
        Schema::dropIfExists('master_bahanbakarmasak');
        Schema::dropIfExists('master_carapembuangansampah');
        Schema::dropIfExists('master_caraperolehanair');
        Schema::dropIfExists('master_desa');
        Schema::dropIfExists('master_kecamatan');
        Schema::dropIfExists('master_kabupaten');
        Schema::dropIfExists('master_provinsi');
        Schema::dropIfExists('master_fasilitastempatbab');
        Schema::dropIfExists('master_hubungankeluarga');
        Schema::dropIfExists('master_hubungankepalakeluarga');
        Schema::dropIfExists('master_ijasahterakhir');
        Schema::dropIfExists('master_imunisasi');
        Schema::dropIfExists('master_jabatan');
        Schema::dropIfExists('master_jenisatapbangunan');
        Schema::dropIfExists('master_jenisbahangalian');
        Schema::dropIfExists('master_jenisdindingbangunan');
        Schema::dropIfExists('master_jenisdisabilitas');
        Schema::dropIfExists('master_jenisfisikbangunan');
        Schema::dropIfExists('master_jeniskelahiran');
        Schema::dropIfExists('master_jeniskelamin');
        Schema::dropIfExists('master_jenislantaibangunan');
        Schema::dropIfExists('master_kondisiatapbangunan');
        Schema::dropIfExists('master_kondisidindingbangunan');
        Schema::dropIfExists('master_kondisilantaibangunan');
        Schema::dropIfExists('master_kondisisumberair');
        Schema::dropIfExists('master_kondisilapanganusaha');
        Schema::dropIfExists('master_manfaatmataair');
        Schema::dropIfExists('master_mutasikeluar');
        Schema::dropIfExists('master_mutasimasuk');
        Schema::dropIfExists('master_omsetusaha');
        Schema::dropIfExists('master_partisipasisekolah');
        Schema::dropIfExists('master_pekerjaan');
        Schema::dropIfExists('master_pembuanganakhirtinja');
        Schema::dropIfExists('master_pendapatanperbulan');
        Schema::dropIfExists('master_penyakitkronis');
        Schema::dropIfExists('master_pertolonganpersalinan');
        Schema::dropIfExists('master_statuskawin');
        Schema::dropIfExists('master_statuskedudukankerja');
        Schema::dropIfExists('master_pemilikbangunan');
        Schema::dropIfExists('master_statuspemiliklahan');
        Schema::dropIfExists('master_statustinggal');
        Schema::dropIfExists('master_sumberairminum');
        Schema::dropIfExists('master_sumberdayaterpasang');
        Schema::dropIfExists('master_sumberpeneranganutama');
        Schema::dropIfExists('master_tempatpersalinan');
        Schema::dropIfExists('master_tempatusaha');
        Schema::dropIfExists('master_tercantumdalamkk');
        Schema::dropIfExists('master_tingkatsulitdisabilitas');
        Schema::dropIfExists('master_jawab');
        Schema::dropIfExists('master_jawabbangun');
        Schema::dropIfExists('master_jawabkonflik');
        Schema::dropIfExists('master_jawabkualitasbayi');
        Schema::dropIfExists('master_jawabkualitasibuhamil');
        Schema::dropIfExists('master_jawablemdes');
        Schema::dropIfExists('master_jawablemek');
        Schema::dropIfExists('master_jawablemmas');
        Schema::dropIfExists('master_jawabprogramserta');
        Schema::dropIfExists('master_jawabsarpras');
        Schema::dropIfExists('master_jawabtempatpersalinan');
        Schema::dropIfExists('master_dusun');
        Schema::dropIfExists('master_inventaris');
    }
};
