<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Kelahiran Penduduk</title>
    <style>
        @page { margin: 15mm; size: A4; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; font-size: 11pt; line-height: 1.5; }
        h1, h2, h3 { color: #111827; margin-bottom: 4px; }
        h1 { font-size: 18pt; text-align: center; margin-bottom: 8px; }
        h2 { font-size: 14pt; margin-top: 16px; }
        p { margin: 0 0 6px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .text-center { text-align: center; }
        .summary { margin-bottom: 16px; }
        .page-break { page-break-before: always; }
        .highlight { background-color: #fef3c7; }
    </style>
</head>
<body>

    <h1>LAPORAN ANALISIS KELAHIRAN PENDUDUK</h1>
    <p><strong>Periode:</strong> {{ $periode }}</p>
    <p><strong>Tanggal Laporan:</strong> {{ $tanggal }}</p>
    <p><strong>Sumber Data:</strong> Modul Kelahiran</p>
    <p><strong>Total Kelahiran:</strong> {{ number_format($totalKelahiran) }} bayi</p>

    <h2>STATISTIK UTAMA</h2>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Persentase (%)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Tempat Persalinan</strong></td>
                <td>
                    @foreach($tempatStat as $t)
                        {{ $t['kategori'] }} ({{ $t['persen'] }}%)<br>
                    @endforeach
                </td>
                <td class="text-center">{{ array_sum(array_column($tempatStat, 'jumlah')) }}</td>
                <td class="text-center">100%</td>
            </tr>
            <tr>
                <td><strong>Jenis Kelahiran</strong></td>
                <td>
                    @foreach($jenisStat as $j)
                        {{ $j['kategori'] }} ({{ $j['persen'] }}%)<br>
                    @endforeach
                </td>
                <td class="text-center">{{ array_sum(array_column($jenisStat, 'jumlah')) }}</td>
                <td class="text-center">100%</td>
            </tr>
            <tr>
                <td><strong>Pertolongan Persalinan</strong></td>
                <td>
                    @foreach($tolongStat as $p)
                        {{ $p['kategori'] }} ({{ $p['persen'] }}%)<br>
                    @endforeach
                </td>
                <td class="text-center">{{ array_sum(array_column($tolongStat, 'jumlah')) }}</td>
                <td class="text-center">100%</td>
            </tr>
            <tr>
                <td><strong>Jenis Kelamin Bayi</strong></td>
                <td>Laki-laki: {{ $persenLaki }}% | Perempuan: {{ $persenPerempuan }}%</td>
                <td class="text-center">{{ $laki + $perempuan }}</td>
                <td class="text-center">100%</td>
            </tr>
            <tr>
                <td><strong>Kelahiran Ke-</strong></td>
                <td>
                    @foreach($kelahiranKe as $k)
                        {{ $k['kategori'] }} ({{ $k['persen'] }}%)<br>
                    @endforeach
                </td>
                <td class="text-center">{{ array_sum(array_column($kelahiranKe, 'jumlah')) }}</td>
                <td class="text-center">100%</td>
            </tr>
            <tr>
                <td><strong>Berat Lahir (gram)</strong></td>
                <td>
                    @foreach($beratStat as $b)
                        {{ $b['kategori'] }} ({{ $b['persen'] }}%)<br>
                    @endforeach
                </td>
                <td class="text-center">{{ array_sum(array_column($beratStat, 'jumlah')) }}</td>
                <td class="text-center">100%</td>
            </tr>
            <tr>
                <td><strong>Panjang Lahir (cm)</strong></td>
                <td>
                    @foreach($panjangStat as $b)
                        {{ $b['kategori'] }} ({{ $b['persen'] }}%)<br>
                    @endforeach
                </td>
                <td class="text-center">{{ array_sum(array_column($panjangStat, 'jumlah')) }}</td>
                <td class="text-center">100%</td>
            </tr>
        </tbody>
    </table>

    <h2>SEBARAN WILAYAH</h2>
    <table>
        <thead>
            <tr>
                <th>Wilayah</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Persentase (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wilayahStat as $w)
            <tr>
                <td>{{ $w['kategori'] }}</td>
                <td class="text-center">{{ $w['jumlah'] }}</td>
                <td class="text-center">{{ $w['persen'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <h2>ANALISIS INTERPRETATIF & REKOMENDASI KEBIJAKAN</h2>
    <table>
        <thead>
            <tr>
                <th>Field Analisis</th>
                <th>Temuan Utama</th>
                <th>Rekomendasi Kebijakan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tempat Persalinan</td>
                <td>Masih terdapat persalinan di rumah.</td>
                <td>Perlu peningkatan fasilitas kesehatan dan sosialisasi persalinan di RS/Puskesmas.</td>
            </tr>
            <tr>
                <td>Jenis Kelahiran</td>
                <td>Didominasi kelahiran tunggal.</td>
                <td>Siapkan layanan neonatal untuk kelahiran ganda (kembar).</td>
            </tr>
            <tr>
                <td>Pertolongan Persalinan</td>
                <td>Masih ditemukan dukun bayi non-medis.</td>
                <td>Lakukan pelatihan dan sertifikasi dukun sebagai tenaga bidan komunitas.</td>
            </tr>
            <tr>
                <td>Kelahiran Ke-</td>
                <td>Persentase anak ke-3 ke atas masih tinggi.</td>
                <td>Perkuat program KB dan edukasi keluarga berencana.</td>
            </tr>
            <tr>
                <td>Berat Lahir</td>
                <td>Bayi BBLR (<2500g) cukup banyak.</td>
                <td>Program gizi ibu hamil dan monitoring kehamilan perlu ditingkatkan.</td>
            </tr>
            <tr>
                <td>Panjang Badan Bayi</td>
                <td>Bayi <45 cm mengindikasikan risiko BBLR.</td>
                <td>Integrasikan program gizi masyarakat dan pemantauan tumbuh kembang.</td>
            </tr>
            <tr>
                <td>Jenis Kelamin Bayi</td>
                <td>Distribusi relatif seimbang antara laki-laki dan perempuan.</td>
                <td>Lanjutkan pemantauan rasio gender untuk proyeksi kependudukan.</td>
            </tr>
            <tr>
                <td>Wilayah</td>
                <td>Kasus BBLR lebih banyak di wilayah terpencil.</td>
                <td>Fokuskan intervensi kesehatan di daerah rural dengan tenaga medis tambahan.</td>
            </tr>
        </tbody>
    </table>

    <h2>INDIKATOR EVALUASI PROGRAM</h2>
    <ul>
        <li>Penurunan persalinan non-medis hingga <strong>&lt;10%</strong> per tahun.</li>
        <li>Peningkatan bayi lahir sehat (≥2500 g dan ≥45 cm) sebesar <strong>20%</strong>.</li>
        <li>Kenaikan jumlah ibu hamil yang memeriksakan kehamilan minimal 4 kali.</li>
        <li>Cakupan layanan persalinan di fasilitas kesehatan mencapai <strong>&gt;90%</strong>.</li>
    </ul>

    <h2>KESIMPULAN UMUM</h2>
    <p>Sistem pelaporan kelahiran memberikan gambaran kondisi kesehatan ibu dan anak di setiap wilayah. Data ini digunakan untuk:</p>
    <ul>
        <li>Memantau tren kelahiran per wilayah dan waktu.</li>
        <li>Menjadi dasar perumusan kebijakan kesehatan ibu & anak.</li>
        <li>Mengevaluasi efektivitas program KB dan gizi masyarakat.</li>
    </ul>

    <p style="text-align:center; margin-top:40px;">🩺 <em>Dinas Kesehatan & Kependudukan Daerah</em> — {{ $tanggal }}</p>

</body>
</html>
