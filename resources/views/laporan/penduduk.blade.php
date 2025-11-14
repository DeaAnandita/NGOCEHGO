<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Penduduk & Intervensi Kemiskinan</title>
<style>
    @page { margin: 15mm; size: A4; }
    body { font-family: 'DejaVu Sans', sans-serif; color:#1f2937; font-size:11pt; line-height:1.6; margin:0; }
    h1,h2 { color:#1e40af; text-align:center; }
    h2 { border-bottom:2px solid #6366f1; padding-bottom:4px; margin-top:20px; }
    table { width:100%; border-collapse:collapse; margin:10px 0; }
    th,td { border:1px solid #e5e7eb; padding:6px; }
    th { background:#eef2ff; text-align:center; }
    .right { text-align:right; }
    .page-break { page-break-before: always; }
    .small { font-size:9pt; color:#6b7280; }
    .catatan { background:#e0e7ff; border-left:5px solid #6366f1; padding:10px; border-radius:8px; margin:10px 0; }
</style>
</head>
<body>
<div class="container">
<h1>Laporan Analisis Data Penduduk Desa</h1>
<p class="small">Periode: <strong>{{ $periode }}</strong> | Dicetak: <strong>{{ $tanggal }}</strong></p>

<h2>1. Statistik Umum Penduduk</h2>
<table>
<thead><tr><th>Kategori</th><th>Jumlah</th><th>Persentase (%)</th></tr></thead>
<tbody>
<tr><td>Total Penduduk</td><td class="right">{{ number_format($totalPenduduk) }}</td><td class="right">100</td></tr>
<tr><td>Laki-laki</td><td class="right">{{ number_format($laki) }}</td><td class="right">{{ $persenLaki }}</td></tr>
<tr><td>Perempuan</td><td class="right">{{ number_format($perempuan) }}</td><td class="right">{{ $persenPerempuan }}</td></tr>
<tr><td>Laki-laki Tidak Bekerja</td><td class="right">{{ number_format(($laki * $persenLakiTidakBekerja / 100)) }}</td><td class="right">{{ $persenLakiTidakBekerja }}</td></tr>
<tr><td>Perempuan Tidak Bekerja</td><td class="right">{{ number_format(($perempuan * $persenPerempuanTidakBekerja / 100)) }}</td><td class="right">{{ $persenPerempuanTidakBekerja }}</td></tr>
<tr><td>Total Tidak Bekerja</td><td class="right">{{ number_format(($totalPenduduk * $persenTidakBekerja / 100)) }}</td><td class="right">{{ $persenTidakBekerja }}</td></tr>
</tbody>
</table>

<div class="catatan">
<b>Interpretasi:</b> Persentase pengangguran tinggi menandakan perlunya intervensi ekonomi dan pelatihan kerja.
</div>

<div class="page-break"></div>

<h2>2. Distribusi Jenis Kelamin & Agama</h2>
<table>
<thead><tr><th>Agama</th><th>Jumlah</th><th>Persentase (%)</th></tr></thead>
<tbody>
@foreach($agamaStat as $row)
<tr><td>{{ $row['nama'] }}</td><td class="right">{{ number_format($row['jumlah']) }}</td><td class="right">{{ $row['persen'] }}</td></tr>
@endforeach
</tbody>
</table>

<div class="page-break"></div>

<h2>3. Mutasi Penduduk</h2>
<table>
<thead><tr><th>Kategori Mutasi</th><th>Jumlah</th><th>Persentase (%)</th></tr></thead>
<tbody>
@foreach($mutasiStat as $row)
<tr><td>{{ $row['nama'] }}</td><td class="right">{{ number_format($row['jumlah']) }}</td><td class="right">{{ $row['persen'] }}</td></tr>
@endforeach
</tbody>
</table>

<div class="page-break"></div>

<h2>4. Struktur Keluarga dan Hubungan Anggota</h2>
<table>
<thead><tr><th>Hubungan</th><th>Jumlah Penduduk</th></tr></thead>
<tbody>
@foreach($hubunganStat as $row)
<tr><td>{{ $row['hubungan'] }}</td><td class="right">{{ number_format($row['jumlah']) }}</td></tr>
@endforeach
</tbody>
</table>

<div class="page-break"></div>

<h2>5. Sebaran Keluarga dan Anggota per Desa</h2>
<table>
<thead><tr><th>Wilayah (Desa, Kecamatan)</th><th>Jumlah Keluarga</th><th>Jumlah Penduduk</th><th>Rata-rata Anggota/KK</th></tr></thead>
<tbody>
@foreach($dataKK as $row)
<tr>
<td>{{ $row['wilayah'] }}</td>
<td class="right">{{ number_format($row['keluarga']) }}</td>
<td class="right">{{ number_format($row['penduduk']) }}</td>
<td class="right">{{ $row['rata'] }}</td>
</tr>
@endforeach
</tbody>
</table>

<div class="page-break"></div>

<h2>6. Rekomendasi Intervensi Pemerintah</h2>
<table>
<thead><tr><th>Indikator</th><th>Analisis</th><th>Rekomendasi</th></tr></thead>
<tbody>
<tr><td>Pengangguran</td><td>{{ $persenTidakBekerja }}% penduduk belum bekerja.</td><td>Program padat karya, pelatihan kerja, bantuan modal usaha kecil.</td></tr>
<tr><td>Mutasi Tinggi</td><td>Mobilitas tinggi berpotensi menyebabkan ketimpangan ekonomi.</td><td>Program reintegrasi keluarga migran dan bantuan adaptasi ekonomi.</td></tr>
<tr><td>Struktur Keluarga</td><td>Banyak tanggungan dalam satu keluarga.</td><td>Bantuan pangan, pendidikan, dan program keluarga berencana.</td></tr>
<tr><td>Agama & Sosial</td><td>Potensi kolaborasi lembaga sosial dan keagamaan.</td><td>Pemberdayaan komunitas berbasis keagamaan.</td></tr>
</tbody>
</table>

<div class="catatan">
<b>Kesimpulan:</b> Berdasarkan data sosial ekonomi dan kependudukan, pemerintah desa dapat memetakan keluarga rentan dan merancang program pengentasan kemiskinan berbasis data yang lebih tepat sasaran.
</div>

</div>
</body>
</html>
