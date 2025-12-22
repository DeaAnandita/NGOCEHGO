<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
@page { margin: 20px 25px; }
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; line-height: 1.5; }
.header { text-align:center; margin-bottom:12px; }
.title { font-size:18px; font-weight:bold; text-transform:uppercase; }
.subtitle { font-size:13px; margin-top:4px; }
.summary { border:1px solid #d1d5db; background:#f9fafb; padding:10px; border-radius:5px; margin-bottom:12px; }
h3 { font-size:13px; margin:16px 0 6px; }
table { width:100%; border-collapse:collapse; margin-top:8px; }
th, td { border:1px solid #d1d5db; padding:8px; font-size:12px; }
th { background:#f3f4f6; text-align:center; }
.box { border:1px solid #d1d5db; border-radius:5px; padding:10px; margin-top:12px; }
.analisis { background:#fff7ed; }
.rekomendasi { background:#f0fdf4; }
.footer { margin-top:20px; font-size:11px; color:#6b7280; }
</style>
</head>
<body>

<div class="header">
    <div class="title">Laporan Analisis Usaha Rumah Tangga</div>
    <div class="subtitle">Periode: {{ $periode }}</div>
</div>

<div class="summary">
    <p><strong>Total Usaha Rumah Tangga Terdata:</strong> {{ $totalUsaha }} usaha</p>
    <p><strong>Rata-rata Jumlah Pekerja per Usaha:</strong> {{ $rataPekerja }} orang</p>
    <p><strong>Lapangan Usaha Dominan:</strong> {{ $lapanganDominan }} ({{ $persenLapanganDominan }}%)</p>
</div>

<!-- Tabel-tabel tetap sama seperti sebelumnya -->
<h3>Distribusi Lapangan Usaha</h3>
<table>
    <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th>Lapangan Usaha</th>
            <th style="width:20%;">Jumlah Usaha</th>
            <th style="width:20%;">Persentase (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lapanganUsaha as $nama => $v)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td>{{ $nama }}</td>
            <td align="center">{{ $v['jumlah'] }}</td>
            <td align="center">{{ $v['persen'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Distribusi Omzet Usaha per Bulan</h3>
<table>
    <thead>
        <tr>
            <th style="width:8%;">No</th>
            <th>Kategori Omzet</th>
            <th style="width:25%;">Jumlah Usaha</th>
            <th style="width:25%;">Persentase (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($omsetUsaha as $nama => $v)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td>{{ $nama }}</td>
            <td align="center">{{ $v['jumlah'] }}</td>
            <td align="center">{{ $v['persen'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Status Kepemilikan Tempat Usaha</h3>
<table>
    <thead>
        <tr>
            <th style="width:8%;">No</th>
            <th>Status Kepemilikan</th>
            <th style="width:25%;">Jumlah Usaha</th>
            <th style="width:25%;">Persentase (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tempatUsaha as $nama => $v)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td>{{ $nama }}</td>
            <td align="center">{{ $v['jumlah'] }}</td>
            <td align="center">{{ $v['persen'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="box analisis">
    <h4>Analisis Interpretatif</h4>
    <p>
        Berdasarkan pengolahan data dari <strong>{{ $totalUsaha }}</strong> usaha rumah tangga terdata pada periode {{ $periode }},
        sektor <strong>{{ $lapanganDominan }}</strong> menjadi lapangan usaha yang paling dominan dengan kontribusi sebesar 
        <strong>{{ $persenLapanganDominan }}%</strong> dari total usaha.
    </p>
    <p>
        Karakteristik usaha rumah tangga secara umum masih berskala mikro, terlihat dari rata-rata tenaga kerja 
        hanya <strong>{{ $rataPekerja }} orang per usaha</strong> dan dominasi kategori omzet 
        <strong>{{ $omsetDominan }}</strong> ({{ $persenOmsetDominan }}%).
        @if($persenOmsetTerendah > 20)
            Khususnya, sekitar <strong>{{ $persenOmsetTerendah }}%</strong> usaha berada pada kategori omzet terendah 
            ({{ $omsetTerendah }}), menunjukkan masih rendahnya skala pendapatan mayoritas pelaku usaha.
        @endif
    </p>
    <p>
        Dari sisi kepemilikan tempat usaha, <strong>{{ $tempatDominan }}</strong> menjadi status yang paling banyak 
        dipilih ({{ $persenTempatDominan }}%).
        @if($persenMilikSendiri >= 50)
            Positifnya, sebanyak <strong>{{ $persenMilikSendiri }}%</strong> usaha telah menggunakan tempat milik sendiri, 
            yang menjadi aset penting untuk stabilitas dan pengembangan usaha jangka panjang.
        @else
            Namun, sebagian besar usaha masih bergantung pada tempat non-milik sendiri, sehingga rentan terhadap 
            risiko kenaikan biaya sewa atau relokasi.
        @endif
    </p>
    <p>
        Secara keseluruhan, penguatan kapasitas produksi, akses modal, dan perluasan pasar menjadi prioritas 
        untuk meningkatkan daya saing usaha rumah tangga di desa.
    </p>
</div>

<div class="box rekomendasi">
    <h4>Rekomendasi Penguatan Usaha Rumah Tangga</h4>
    <ul>
        <li>Prioritaskan program pemberdayaan pada sektor dominan <strong>{{ $lapanganDominan }}</strong> 
            melalui pelatihan khusus, bantuan alat produksi, dan pengembangan klaster usaha.</li>

        <li>Tingkatkan kapasitas pelaku usaha dengan omzet rendah (kategori {{$omsetDominan}}) 
            melalui pendampingan manajemen keuangan, akses kredit mikro, dan inovasi produk.</li>

        <li>Dorong kepemilikan tempat usaha tetap melalui fasilitasi sertifikasi tanah, 
            program kredit lunak untuk renovasi/pembangunan kios, atau penyediaan ruang usaha bersama.</li>

        <li>Kembangkan strategi pemasaran digital berbasis desa (marketplace lokal, media sosial kelompok usaha, 
            live selling) untuk meningkatkan akses pasar produk {{ $lapanganDominan }} dan kategori usaha lainnya.</li>

        <li>Lakukan monitoring dan evaluasi berkala (setiap 6–12 bulan) terhadap perkembangan usaha 
            pasca-intervensi untuk mengukur dampak program.</li>
    </ul>
</div>

<div class="footer">
    <p>Laporan ini dihasilkan secara otomatis oleh <strong>Sistem NGOCEH GO</strong> berdasarkan data aktual dari website.</p>
    <p>Tanggal Cetak: {{ $tanggal }}</p>
</div>

</body>
</html>