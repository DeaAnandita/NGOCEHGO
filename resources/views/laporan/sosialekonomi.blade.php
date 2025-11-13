<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Sosial Ekonomi</title>
    <style>
        @page { margin: 18mm; size: A4; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10.5pt; line-height: 1.5; }
        .container { max-width: 780px; margin: 0 auto; }
        h1 { text-align: center; font-size: 16pt; font-weight: bold; margin: 0 0 5px; }
        .periode { text-align: center; font-size: 12pt; margin-bottom: 18px; }

        table { width: 100%; border-collapse: collapse; font-size: 10pt; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; }
        th { background: #e0e7ff; font-weight: bold; text-align: center; }
        tr:nth-child(even) { background: #f9fafb; }

        .kode { width: 12%; text-align: center; font-weight: bold; }
        .label { width: 58%; }
        .jumlah, .persen { width: 15%; text-align: center; }
        .analysis-box { background: #f8fafc; border-left: 5px solid #1e40af; padding: 12px; margin-bottom: 20px; border-radius: 0 6px 6px 0; }
        .footer { font-size: 11px; color: #666; border-top: 1px solid #ccc; padding-top: 6px; margin-top: 30px; }
        .page-break { page-break-before: always; } 
    </style>
</head>
<body>
<div class="container">
    <h1>LAPORAN ANALISIS SOSIAL EKONOMI</h1>
    <div class="periode">Periode: {{ now('Asia/Singapore')->translatedFormat('F Y') }}</div>

    <!-- TOTAL PENDUDUK -->
    <div class="analysis-box">
        <p><strong>Total penduduk:</strong> {{ $summary['total_penduduk'] ?? 0 }}</p>
    </div>

    <!-- REKAP TABEL PER FIELD -->
    @foreach($summary as $field => $items)
        @if($field != 'total_penduduk')
            <table>
                <caption style="text-align: left; font-weight: bold; margin-bottom: 6px;">
                    {{ ucwords(str_replace('_',' ',$field)) }}
                </caption>
                <thead>
                    <tr>
                        <th class="kode">Kode</th>
                        <th class="label">Keterangan</th>
                        <th class="jumlah">Jumlah</th>
                        <th class="persen">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                    <td class="kode">{{ $item['kode'] ?? '-' }}</td>
                    <td class="label">{{ $item['label'] ?? 'Tidak diketahui' }}</td>
                    <td class="jumlah">{{ $item['jumlah'] }}</td>
                    <td class="persen">{{ $item['persen'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <!-- ANALISIS INTERPRETATIF 9 FIELD -->
    <div class="page-break"></div>
    <div class="analysis-box">
        <h3>Analisis Interpretatif & Rekomendasi Kebijakan</h3>
        <table>
            <thead>
                <tr style="background: #1e40af; color: white;">
                    <th style="width: 22%;">Field</th>
                    <th style="width: 38%;">Analisis</th>
                    <th style="width: 40%;">Rekomendasi Kebijakan</th>
                </tr>
            </thead>

<tbody>
@foreach($analysis as $field => $info)
    @php
        $kode = $info['max_kode'] ?? null;
        $nama = $info['max_nama'] ?? '-';
        $persen = $info['persen'] ?? 0;
        $jumlah = $info['jumlah'] ?? 0;
        $rekom = '';
        $interpretasi = '';
    @endphp

    @switch($field)
        {{-- ========================================
             1. PARTISIPASI SEKOLAH
        ========================================= --}}
        @case('partisipasisekolah')
            @switch($kode)
                @case(1)
                    @php
                        $interpretasi = "Mayoritas masih di jenjang dasar ({$nama}), butuh pendampingan agar tidak putus sekolah.";
                        $rekom = "Subsidi pendidikan dasar, beasiswa siswa miskin, program gizi anak sekolah, pendampingan literasi dasar.";
                    @endphp
                    @break
                @case(2)
                    @php
                        $interpretasi = "Banyak anak di jenjang SMP/MTs, usia remaja dengan risiko putus sekolah tinggi.";
                        $rekom = "Beasiswa prestasi, bantuan alat sekolah, pelatihan remaja produktif, literasi digital.";
                    @endphp
                    @break
                @case(3)
                    @php
                        $interpretasi = "Mayoritas sudah di tingkat SMA/SMK, tahap menjelang kerja.";
                        $rekom = "Pelatihan vokasi, bimbingan karier, program wirausaha muda desa.";
                    @endphp
                    @break
                @case(4)
                    @php
                        $interpretasi = "Sebagian kecil sudah perguruan tinggi.";
                        $rekom = "Beasiswa KIP-K, magang desa, pengabdian pendidikan ke desa.";
                    @endphp
                    @break
                @case(5)
                    @php
                        $interpretasi = "Cukup banyak yang tidak bersekolah lagi di usia produktif.";
                        $rekom = "Program kejar paket, pelatihan keterampilan kerja, pendidikan keaksaraan.";
                    @endphp
                    @break
                @case(6)
                    @php
                        $interpretasi = "Sebagian belum pernah sekolah, buta huruf atau belum terakses pendidikan dasar.";
                        $rekom = "Program literasi masyarakat, PAUD non-formal, subsidi pendidikan dasar anak miskin.";
                    @endphp
                    @break
            @endswitch
            @break

        {{-- ========================================
             2. TINGKAT SULIT DISABILITAS
        ========================================= --}}
        @case('tingkat_sulit')
            @switch($kode)
                @case(1)
                    @php
                        $interpretasi = "Sebagian memiliki sedikit kesulitan dalam aktivitas sehari-hari.";
                        $rekom = "Pelatihan kerja inklusif untuk penyandang disabilitas ringan.";
                    @endphp
                    @break
                @case(2)
                    @php
                        $interpretasi = "Banyak mengalami kesulitan berat.";
                        $rekom = "Bantuan alat bantu (kursi roda, alat dengar), pelatihan kerja inklusif, tunjangan sosial.";
                    @endphp
                    @break
                @case(3)
                    @php
                        $interpretasi = "Sebagian besar tidak bisa melakukan aktivitas tanpa bantuan.";
                        $rekom = "Bantuan alat bantu dan tunjangan sosial rutin.";
                    @endphp
                    @break
                @default
                    @php
                        $interpretasi = "Mayoritas tidak memiliki disabilitas berat.";
                        $rekom = "Tidak termasuk target intervensi disabilitas.";
                    @endphp
                    @break
            @endswitch
            @break

        {{-- ========================================
             3. STATUS KERJA
        ========================================= --}}
        @case('status_kerja')
            @if(in_array($kode, [1,2,3]))
                @php
                    $interpretasi = "Mayoritas berusaha sendiri atau dibantu buruh — sektor usaha mandiri aktif.";
                    $rekom = "Pelatihan kewirausahaan, akses modal mikro, diversifikasi usaha.";
                @endphp
            @elseif(in_array($kode, [4,5]))
                @php
                    $interpretasi = "Mayoritas bekerja formal dengan gaji tetap.";
                    $rekom = "Program jaminan sosial & peningkatan kompetensi.";
                @endphp
            @elseif(in_array($kode, [6,7,8]))
                @php
                    $interpretasi = "Banyak pekerja informal atau tidak dibayar — kelompok rentan ekonomi.";
                    $rekom = "Jaminan sosial tenaga kerja informal, pelatihan keterampilan & akses modal kecil.";
                @endphp
            @endif
            @break

        {{-- ========================================
             4. PENYAKIT KRONIS
        ========================================= --}}
        @case('penyakit_kronis')
            @php
                $interpretasi = "Kasus terbanyak: {$nama}.";
                $rekom = match($kode) {
                    1 => 'Program kesehatan preventif & subsidi obat hipertensi.',
                    5 => 'Edukasi pola makan, subsidi obat insulin & cek gula darah rutin.',
                    7 => 'Rehabilitasi dan bantuan alat bantu jalan.',
                    12 => 'Program ARV gratis dan konseling HIV/AIDS.',
                    default => 'Pemeriksaan kesehatan rutin & edukasi gaya hidup sehat.'
                };
            @endphp
            @break

        {{-- ========================================
             5. PENDAPATAN PER BULAN
        ========================================= --}}
        @case('pendapatan')
            @switch($kode)
                @case(1)
                    @php
                        $interpretasi = "Mayoritas penduduk sangat miskin (≤ Rp1 juta).";
                        $rekom = "BLT, subsidi pangan, bantuan modal mikro.";
                    @endphp
                    @break
                @case(2)
                    @php
                        $interpretasi = "Sebagian besar rentan miskin (Rp1–1,5 juta).";
                        $rekom = "Pelatihan keterampilan, akses kredit mikro.";
                    @endphp
                    @break
                @case(3)
                    @php
                        $interpretasi = "Kelas menengah bawah (Rp1,5–2 juta).";
                        $rekom = "Penguatan UMKM & diversifikasi usaha.";
                    @endphp
                    @break
                @case(4)
                    @php
                        $interpretasi = "Pendapatan cukup stabil (Rp2–3 juta).";
                        $rekom = "Tabungan produktif & akses perbankan.";
                    @endphp
                    @break
                @case(5)
                    @php
                        $interpretasi = "Kelas menengah atas (≥ Rp3 juta).";
                        $rekom = "Edukasi investasi & kewirausahaan sosial.";
                    @endphp
                    @break
            @endswitch
            @break

        {{-- ========================================
             6. LAPANGAN USAHA
        ========================================= --}}
        @case('lapangan_usaha')
            @if($kode >= 1 && $kode <= 7)
                @php
                    $interpretasi = "Dominan di sektor primer (pertanian, peternakan, perikanan).";
                    $rekom = "Modernisasi pertanian, pelatihan teknologi tepat guna, akses pupuk & pasar.";
                @endphp
            @elseif(in_array($kode, [12,13]))
                @php
                    $interpretasi = "Didominasi sektor jasa UMKM (perdagangan, kuliner).";
                    $rekom = "Digitalisasi pemasaran & pelatihan kewirausahaan.";
                @endphp
            @elseif($kode == 20)
                @php
                    $interpretasi = "Sebagian bekerja sebagai pemulung, kelompok sangat rentan.";
                    $rekom = "Bantuan sosial & pelatihan daur ulang.";
                @endphp
            @else
                @php
                    $interpretasi = "Sektor beragam.";
                    $rekom = "Pendampingan usaha sesuai potensi dominan.";
                @endphp
            @endif
            @break

        {{-- ========================================
             7. IMUNISASI
        ========================================= --}}
        @case('imunisasi')
            @if($kode <= 9)
                @php
                    $interpretasi = "Sebagian anak belum lengkap imunisasi.";
                    $rekom = "Program imunisasi gratis & edukasi posyandu.";
                @endphp
            @else
                @php
                    $interpretasi = "Mayoritas anak sudah imunisasi lengkap.";
                    $rekom = "Pertahankan program imunisasi rutin.";
                @endphp
            @endif
            @break
{{-- ========================================
     8. IJAZAH TERAKHIR
========================================= --}}
@case('ijasah_terakhir')
    @switch($kode)
        @case(0)
            @php
        $interpretasi = "Sebagian masyarakat tidak memiliki ijazah formal.";
        $rekom = "Program keaksaraan fungsional, kejar paket A, serta penyuluhan pentingnya pendidikan dasar.";
            @endphp
            @break
        @case(1)
            @php
        $interpretasi = "Mayoritas masyarakat berijazah SD atau sederajat.";
        $rekom = "Program kejar paket B & C, pelatihan keterampilan dasar, dan pemberdayaan ekonomi produktif.";
            @endphp
            @break
        @case(2)
            @php
                $interpretasi = "Sebagian besar masyarakat berijazah SMP atau sederajat.";
                $rekom = "Pelatihan vokasional, beasiswa kejar paket C, dan wirausaha muda produktif.";
            @endphp
            @break
        @case(3)
            @php
                $interpretasi = "Mayoritas masyarakat berpendidikan SMA/SMK sederajat.";
                $rekom = "Program pelatihan kerja terapan, magang industri, dan peningkatan softskill tenaga muda.";
            @endphp
            @break
        @case(4)
            @php
                $interpretasi = "Sebagian masyarakat berijazah D1.";
                $rekom = "Sertifikasi profesi, peningkatan keterampilan teknis, dan penempatan kerja lokal.";
            @endphp
            @break
        @case(5)
            @php
                $interpretasi = "Sebagian masyarakat berijazah D2.";
                $rekom = "Pelatihan profesional lanjutan dan program inkubasi kewirausahaan terapan.";
            @endphp
            @break
        @case(6)
            @php
                $interpretasi = "Sebagian masyarakat berijazah D3.";
                $rekom = "Program digitalisasi UMKM, pelatihan produktivitas, dan kemitraan dunia usaha.";
            @endphp
            @break
        @case(7)
            @php
                $interpretasi = "Sebagian masyarakat berijazah D4/S1.";
                $rekom = "Program inovasi desa berbasis sarjana, riset terapan, dan insentif wirausaha muda.";
            @endphp
            @break
        @case(8)
            @php
                $interpretasi = "Sebagian kecil masyarakat berijazah S2.";
                $rekom = "Pelibatan akademisi lokal dalam perencanaan kebijakan desa dan pengembangan SDM unggul.";
            @endphp
            @break
        @case(9)
            @php
                $interpretasi = "Sebagian sangat kecil masyarakat berijazah S3.";
                $rekom = "Kolaborasi riset, mentoring inovasi sosial, dan transfer pengetahuan kepada masyarakat.";
            @endphp
            @break
        @default
            @php
            $interpretasi = "Data ijazah terakhir belum tersedia atau belum valid.";
            $rekom = "Lakukan verifikasi dan pembaruan data pendidikan masyarakat.";
            @endphp
    @endswitch
@break


{{-- ========================================
     9. JENIS DISABILITAS
========================================= --}}
@case('jenis_disabilitas')
    @switch($kode)
        @case(1)
            @php
                $interpretasi = "Sebagian masyarakat mengalami gangguan penglihatan.";
                $rekom = "Program bantuan kacamata gratis, deteksi dini gangguan mata, dan pelatihan orientasi mobilitas bagi tuna netra.";
            @endphp
            @break

        @case(2)
            @php
                $interpretasi = "Sebagian masyarakat mengalami gangguan pendengaran.";
                $rekom = "Bantuan alat bantu dengar, pelatihan komunikasi inklusif, dan pemeriksaan kesehatan telinga rutin.";
            @endphp
            @break

        @case(3)
            @php
                $interpretasi = "Sebagian masyarakat memiliki kesulitan berjalan atau naik tangga.";
                $rekom = "Program rehabilitasi fisik, bantuan alat bantu jalan, serta perbaikan aksesibilitas fasilitas umum.";
            @endphp
            @break

        @case(4)
            @php
                $interpretasi = "Sebagian masyarakat mengalami kesulitan mengingat atau konsentrasi (pikun).";
                $rekom = "Penyuluhan kesehatan lansia, pemeriksaan fungsi kognitif, dan pendampingan keluarga.";
            @endphp
            @break

        @case(5)
            @php
                $interpretasi = "Sebagian memiliki kesulitan mengurus diri sendiri.";
                $rekom = "Pelatihan perawatan keluarga, bantuan sosial bagi penyandang disabilitas berat.";
            @endphp
            @break

        @case(6)
            @php
                $interpretasi = "Sebagian mengalami kesulitan komunikasi.";
                $rekom = "Terapi wicara, pelatihan komunikasi alternatif, dan pendampingan sosial.";
            @endphp
            @break

        @case(7)
            @php
                $interpretasi = "Sebagian masyarakat mengalami gangguan emosi atau perilaku (depresi/autis).";
                $rekom = "Pendampingan psikologis, konseling keluarga, dan dukungan sosial kesehatan jiwa.";
            @endphp
            @break

        @case(8)
            @php
                $interpretasi = "Sebagian mengalami kelumpuhan anggota tubuh.";
                $rekom = "Rehabilitasi medik, pemberian kursi roda, dan program pelatihan kerja bagi penyandang disabilitas fisik.";
            @endphp
            @break

        @case(9)
            @php
                $interpretasi = "Sebagian masyarakat memiliki kondisi sumbing (bibir/langit-langit).";
                $rekom = "Program operasi gratis, rehabilitasi pasca operasi, dan dukungan sosial bagi penyandang disabilitas wajah.";
            @endphp
            @break

        @case(10)
            @php
                $interpretasi = "Terdapat warga dengan gangguan jiwa berat (gila).";
                $rekom = "Rehabilitasi sosial, dukungan keluarga, dan pengobatan kesehatan jiwa rutin.";
            @endphp
            @break

        @case(11)
            @php
                $interpretasi = "Sebagian mengalami stres atau tekanan psikologis.";
                $rekom = "Kegiatan sosial rekreatif, layanan konseling, dan dukungan kesehatan mental masyarakat.";
            @endphp
            @break

        @case(12)
            @php
                $interpretasi = "Mayoritas masyarakat tidak mengalami disabilitas.";
                $rekom = "Tidak termasuk prioritas intervensi program disabilitas, namun tetap perlu inklusi sosial.";
            @endphp
            @break

        @default
            @php
                $interpretasi = "Data jenis disabilitas tidak terdeteksi dengan jelas.";
                $rekom = "Perlu validasi ulang dan pemutakhiran data disabilitas.";
            @endphp
    @endswitch
    @break

        {{-- Default jika belum ada mapping --}}
        @default
            @php
                $interpretasi = "Data tidak tersedia.";
                $rekom = "-";
            @endphp
    @endswitch

    <tr>
        <td><strong>{{ ucwords(str_replace('_',' ',$field)) }}</strong></td>
        <td>{{ $interpretasi }}</td>
        <td>{{ $rekom }}</td>
    </tr>
@endforeach
</tbody>
