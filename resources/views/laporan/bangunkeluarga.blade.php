<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px 25px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 14px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .subtitle {
            font-size: 13px;
            margin-top: 4px;
        }
        .summary {
            border: 1px solid #d1d5db;
            padding: 10px;
            background: #f9fafb;
            border-radius: 5px;
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 6px;
            font-size: 11px;
            vertical-align: top;
        }
        th {
            background: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #fafafa;
        }
        .text-center {
            text-align: center;
        }
        .badge-tinggi {
            background: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }
        .badge-rendah {
            background: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }
        .badge {
            background: #fef9c3;
            color: #854d0e;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }
        .rekomendasi {
            border: 1px solid #d1d5db;
            background: #f0fdf4;
            padding: 10px;
            border-radius: 5px;
            margin-top: 14px;
        }
        .rekomendasi h4 {
            margin: 0 0 6px;
            font-size: 13px;
            color: #166534;
        }
        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <p class="title">Laporan Analisis Bangun Keluarga</p>
    <p class="subtitle">
        Periode {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
    </p>
</div>

{{-- RINGKASAN --}}
<div class="summary">
    <p><strong>Total Keluarga Terdata:</strong> {{ $data->count() }} keluarga</p>
    <p><strong>Tingkat Pemenuhan Indikator (Rata-rata):</strong> {{ number_format($skor, 2) }}%</p>
    <p><strong>Interpretasi Kondisi Desa:</strong> {{ $kategori }}</p>
</div>

{{-- TABEL INDIKATOR --}}
<h3>Rekapitulasi Pemenuhan Indikator Bangun Keluarga</h3>

<table>
    <thead>
        <tr>
            <th style="width:40px;">No</th>
            <th>Indikator Pembangunan Keluarga</th>
            <th style="width:160px;">Jumlah & Persentase</th>
            <th style="width:110px;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach(range(1,51) as $i)
            @php
                $item = $indikator['indikator_'.$i] ?? ['jumlah'=>0,'persen'=>0];
            @endphp
            <tr>
                <td class="text-center">{{ $i }}</td>
                <td>
                    {{ $master->where('kdpembangunankeluarga',$i)->first()->pembangunankeluarga ?? 'Indikator '.$i }}
                </td>
                <td class="text-center">
                    <strong>{{ $item['jumlah'] }}</strong> keluarga<br>
                    ({{ $item['persen'] }}%)
                </td>
                <td class="text-center">
                    @if($item['persen'] >= 70)
                        <span class="badge-tinggi">BAIK</span>
                    @elseif($item['persen'] >= 40)
                        <span class="badge">SEDANG</span>
                    @else
                        <span class="badge-rendah">PERLU PERHATIAN</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- ANALISIS PER VARIABEL --}}
<h3>Analisis dan Interpretasi Per Variabel Pembangunan Keluarga</h3>

@foreach($analisisVariabel as $v)
<div class="summary">
    <p><strong>{{ $v['nama'] }}</strong></p>
    <p>
        Skor Rata-rata:
        <strong>{{ number_format($v['skor'], 2) }}%</strong>
        —
        @if($v['kategori'] === 'Baik')
            <span class="badge-tinggi">BAIK</span>
        @elseif($v['kategori'] === 'Sedang')
            <span class="badge">SEDANG</span>
        @else
            <span class="badge-rendah">PERLU PERHATIAN</span>
        @endif
    </p>
    <p>{{ $v['interpretasi'] }}</p>
</div>
@endforeach


{{-- REKOMENDASI --}}
<div class="rekomendasi">
    <h4>Rekomendasi Kebijakan Pemerintah Desa</h4>
    <ul>
        @foreach($rekomendasi as $r)
            <li>{{ $r }}</li>
        @endforeach
    </ul>
</div>

{{-- FOOTER --}}
<div class="footer">
    <p>
        Laporan ini dihasilkan secara otomatis oleh
        <strong>Sistem Informasi Pembangunan Keluarga</strong>.
    </p>
    <p>
        <em>Tanggal Cetak:</em>
        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </p>
</div>

</body>
</html>
