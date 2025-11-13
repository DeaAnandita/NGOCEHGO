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

<div class="title">LAPORAN ANALISIS KUALITAS IBU HAMIL</div>
<div class="subtitle">Periode: {{ $periode }}</div>

<div class="box">
    <p><strong>Total Keluarga Terdata:</strong> {{ $total }}</p>
    <p><strong>Skor Kualitas Ibu Hamil:</strong> {{ $skor }} / 100</p>
    <p><strong>Kategori:</strong> {{ $kategori }}</p>
    <p><strong>Pemeriksaan Medis (1–5):</strong> {{ round($pemeriksaanMedis,1) }}%</p>
</div>

<h3>Persentase Indikator Kualitas Ibu Hamil</h3>

<table>
    <thead>
        <tr>
            <th style="width:50px;">Kode</th>
            <th>Nama Indikator</th>
            <th style="width:120px;">Persentase ADA (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($master as $item)
            <tr>
                <td align="center">{{ $item->kdkualitasibuhamil }}</td>
                <td>{{ $item->kualitasibuhamil }}</td>
                <td align="center">{{ $persen[$item->kdkualitasibuhamil] ?? 0 }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h3>Indikator Risiko Utama</h3>

<table>
    <tbody>
        <tr><td>Ibu hamil tidak periksa kesehatan</td><td align="center">{{ $persen[7] ?? 0 }}%</td></tr>
        <tr><td>Periksa ke dukun terlatih</td><td align="center">{{ $persen[6] ?? 0 }}%</td></tr>
        <tr><td>Kematian ibu hamil & nifas</td><td align="center">{{ ($persen[8]??0) + ($persen[11]??0) + ($persen[13]??0) }}%</td></tr>
        <tr><td>Ibu nifas sakit</td><td align="center">{{ $persen[10] ?? 0 }}%</td></tr>
        <tr><td>Pemeriksaan medis (1–5)</td><td align="center">{{ round($pemeriksaanMedis,1) }}%</td></tr>
    </tbody>
</table>

<div class="page-break"></div>

<h3>5 Indikator Paling Banyak Dipenuhi (“ADA”)</h3>

<table>
    <thead>
        <tr>
            <th>Indikator</th>
            <th>Jumlah ADA</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topKualitas as $i)
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
