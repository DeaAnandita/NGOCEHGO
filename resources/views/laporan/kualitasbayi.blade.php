<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px 25px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#111; line-height:1.5; }

        .title { text-align:center; font-size:20px; font-weight:bold; margin-bottom:5px; }
        .subtitle { text-align:center; font-size:13px; margin-bottom:15px; }

        table { width:100%; border-collapse:collapse; margin:12px 0; }
        th, td { border:1px solid #d1d5db; padding:7px; }
        th { background:#f3f4f6; font-weight:bold; text-align:center; }
        tr:nth-child(even) { background:#fafafa; }

        .box { padding:10px; background:#f9fafb; border:1px solid #d1d5db; border-radius:5px; margin-bottom:12px; }

        .rekomendasi { background:#f0fdf4; border:1px solid #86efac; padding:10px; border-radius:5px; margin-top:12px; }
        .rekomendasi h4 { margin:0 0 6px; color:#166534; }

        .footer { margin-top:20px; font-size:11px; text-align:center; color:#6b7280; }
        .page-break { page-break-before:always; }
    </style>
</head>

<body>

<div class="title">LAPORAN ANALISIS KUALITAS BAYI</div>
<div class="subtitle">Periode: {{ $periode }}</div>

<div class="box">
    <p><strong>Total Keluarga Terdata:</strong> {{ $totalKeluarga }}</p>
    <p><strong>Jumlah Bayi Kategori Baik:</strong> {{ $baik }} ({{ $persenBaik }}%)</p>
    <p><strong>Jumlah Bayi Kategori Sedang:</strong> {{ $sedang }} ({{ $persenSedang }}%)</p>
    <p><strong>Jumlah Bayi Kategori Buruk:</strong> {{ $buruk }} ({{ $persenBuruk }}%)</p>
    <p><strong>Kategori Dominan:</strong> {{ $dominan }} ({{ $persenDominan }}%)</p>
</div>

<h3>Persentase Indikator Kualitas Bayi</h3>

<table>
    <thead>
        <tr>
            <th style="width:50px;">Kode</th>
            <th>Nama Indikator</th>
            <th style="width:120px;">Persentase ADA (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($master as $kode => $nama)
            <tr>
                <td align="center">{{ $kode }}</td>
                <td>{{ $nama }}</td>
                <td align="center">{{ $persen[$kode] ?? 0 }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="page-break"></div>

<h3>5 Indikator Paling Banyak Dipenuhi (“ADA”)</h3>

<table>
    <thead>
        <tr>
            <th>Indikator</th>
            <th style="width:140px;" align="center">Jumlah ADA</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topAset as $i)
            <tr>
                <td>{{ $i['nama'] }}</td>
                <td align="center">{{ $i['jumlah'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="page-break"></div>

<h3>Rekomendasi Kebijakan</h3>
<div class="rekomendasi">
    <h4>Saran Intervensi:</h4>
    <ul>
        @foreach($rekomendasi as $r)
            <li>{{ $r }}</li>
        @endforeach
    </ul>
</div>

<div class="footer">
    Laporan ini dihasilkan otomatis oleh Sistem Pembangunan Keluarga<br>
    Tanggal Cetak: {{ $tanggal }}
</div>

</body>
</html>
