<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">

<style>
@page { margin: 20px; }

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 9px;
    color: #111;
}

h2 {
    font-size: 14px;
    text-align: center;
    margin-bottom: 2px;
}

h3 {
    font-size: 11px;
    margin-top: 12px;
    margin-bottom: 6px;
}

/* ================= TABLE UMUM ================= */
table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

th, td {
    border: 1px solid #ccc;
    padding: 4px;
    font-size: 8.5px;
    text-align: center;
    vertical-align: middle;
    word-wrap: break-word;
}

th {
    background-color: #f3f4f6;
    font-weight: bold;
}

td small {
    font-size: 7.5px;
    color: #555;
}

.col-no { width: 28px; }
.col-sarpras { width: 140px; text-align: left; }
.col-dominan { width: 120px; text-align: left; font-weight: bold; }

/* ================= BOX ================= */
.section-box {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 8px 10px;
    margin-bottom: 10px;
    background-color: #fafafa;
}

.section-box p {
    margin: 4px 0;
    font-size: 9px;
}

.section-title {
    font-size: 11px;
    font-weight: bold;
    margin-bottom: 6px;
    border-bottom: 1px solid #d1d5db;
    padding-bottom: 3px;
}

/* ================= ANALISIS ================= */
.analysis {
    margin-top: 20px;
}

.analysis p {
    margin: 4px 0 0 16px;
    padding: 6px 8px;
    border-left: 4px solid #2563eb;
    background-color: #ffffff;
    line-height: 1.4;
}

.analysis .dominan {
    font-weight: bold;
    color: #991b1b;
}

/* ================= REKOMENDASI (WARNA DIUBAH SAJA) ================= */
.rekomendasi {
    background-color: #f0fdf4;        /* hijau muda */
    border: 1px solid #22c55e;        /* hijau */
}

.rekomendasi .section-title {
    color: #166534;                   /* hijau tua */
    border-bottom: 1px solid #22c55e;
}

.rekomendasi ul {
    margin: 6px 0 0 18px;
    padding: 0;
}

.rekomendasi li {
    margin-bottom: 6px;
    padding: 6px 8px;
    background-color: #ffffff;
    border-left: 4px solid #16a34a;   /* aksen hijau */
    line-height: 1.5;
    font-size: 8.8px;
}
</style>
</head>

<body>

<h2>LAPORAN ANALISIS SARANA DAN PRASARANA KERJA</h2>
<p style="text-align:center">Periode {{ $periode }}</p>

<!-- ================= RINGKASAN ================= -->
<div class="section-box">
    <p><strong>Total Keluarga Terdata:</strong> {{ $totalKeluarga }} keluarga</p>
    <p><strong>Tingkat Pemenuhan Indikator (Rata-rata):</strong> {{ number_format($skorRataRata,2) }}%</p>
    <p><strong>Interpretasi Kondisi Desa:</strong> {{ $dominan }}</p>
</div>

<!-- ================= REKAP SARPRAS ================= -->
<h3>Rekap Status Kepemilikan Sarpras</h3>

<table>
<thead>
<tr>
    <th rowspan="2" class="col-no">No</th>
    <th rowspan="2" class="col-sarpras">Sarpras</th>
    <th colspan="{{ count($jawabMaster) }}">Status Kepemilikan</th>
    <th rowspan="2" class="col-dominan">Dominan</th>
</tr>
<tr>
@foreach($jawabMaster as $label)
    <th>{{ \Illuminate\Support\Str::limit($label, 18) }}</th>
@endforeach
</tr>
</thead>

<tbody>
@foreach($rekapDetail as $i => $row)
<tr>
    <td class="col-no">{{ $i + 1 }}</td>
    <td class="col-sarpras">{{ $row['nama'] }}</td>

    @foreach($jawabMaster as $kd => $label)
        <td>
            {{ $row['detail'][$kd]['jumlah'] }}
            <br>
            <small>{{ $row['detail'][$kd]['persen'] }}%</small>
        </td>
    @endforeach

    <td class="col-dominan">{{ $row['dominan'] }}</td>
</tr>
@endforeach
</tbody>
</table>

<!-- ================= ANALISIS ================= -->
<div class="section-box analysis">
    <div class="section-title">Analisis Interpretatif</div>

    @foreach($rekapDetail as $row)
        <p>
            <strong>{{ strtoupper($row['nama']) }}</strong>
            didominasi
            <span class="dominan">{{ strtoupper($row['dominan']) }}</span>.
            @if($row['kodeDominan'] == 6)
                Mayoritas keluarga belum memiliki sarana ini.
            @elseif($row['kodeDominan'] == 2)
                Sarana tersedia namun dalam kondisi tidak layak.
            @elseif(in_array($row['kodeDominan'], [3,4,5]))
                Kepemilikan masih bergantung pihak lain.
            @else
                Sarana sudah mendukung produktivitas ekonomi keluarga.
            @endif
        </p>
    @endforeach
</div>

<!-- ================= REKOMENDASI ================= -->
<div class="section-box rekomendasi">
    <div class="section-title">Rekomendasi</div>
    <ul>
        @foreach($rekomendasi as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
</div>

<p style="text-align:center; margin-top:10px">
    <em>Tanggal Cetak: {{ $tanggal }}</em>
</p>

</body>
</html>
