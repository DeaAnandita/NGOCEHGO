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
        .header { text-align: center; margin-bottom: 12px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; text-transform: uppercase; }
        .subtitle { font-size: 13px; margin: 4px 0 10px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 12px;
            vertical-align: top;
        }
        th {
            background-color: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }
        tr:nth-child(even) { background: #fafafa; }
        .summary {
            border: 1px solid #d1d5db;
            padding: 10px;
            background: #f9fafb;
            border-radius: 5px;
            margin-bottom: 12px;
        }
        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #6b7280;
        }
        h3 {
            font-size: 13px;
            margin-top: 16px;
            margin-bottom: 6px;
        }
        .rekomendasi {
            border: 1px solid #d1d5db;
            background: #f0fdf4;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .rekomendasi h4 {
            margin: 0 0 6px;
            font-size: 13px;
            color: #166534;
        }
        .rekomendasi ul {
            margin: 0;
            padding-left: 18px;
        }
        .rekomendasi li {
            margin-bottom: 4px;
        }
        .category-box {
            text-align: center;
            padding: 12px;
            background: #e0f2fe;
            border: 2px solid #0ea5e9;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Analisis Data Keluarga</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Keluarga Terdata:</strong> {{ number_format($data->count()) }}</p>
        <p><strong>Persentase Mutasi Datang:</strong> {{ $persen_datang }}%</p>
        <p><strong>Rata-rata Kepadatan per Dusun:</strong> {{ $kepadatan_per_dusun }} keluarga</p>
        <p><strong>Skor Kerentanan Kemiskinan:</strong> {{ $skor }} / 100</p>
        <div class="category-box">
            Kategori: {{ $kategori }}
        </div>
    </div>

    <h3>Distribusi per Dusun</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Dusun</th>
                <th>Jumlah Keluarga</th>
                <th>% Mutasi Datang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($masterDusun as $item)
                @php
                    $count = $data->where('kddusun', $item->kddusun)->count();
                    $datang = $data->where('kddusun', $item->kddusun)->where('kdmutasimasuk', 'datang')->count();
                    $persen = $count > 0 ? round(($datang / $count) * 100, 2) : 0;
                @endphp
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td>{{ $item->dusun }}</td>
                    <td align="center">{{ $count }}</td>
                    <td align="center">{{ $persen }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Ringkasan Migrasi</h3>
    <div class="summary">
        <p>• <strong>Persentase Kepadatan Tinggi:</strong> {{ $persen_kepadatan_tinggi }}%</p>
        <p>• <strong>Persentase Migran dari Daerah Miskin:</strong> {{ $persen_migran_miskin }}%</p>
    </div>

    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>
            Berdasarkan data, mayoritas keluarga berada pada kategori 
            <strong>{{ $kategori }}</strong>. 
            @if($kategori === 'Rentan Kemiskinan Migrasi')
                Tinggi influx migran dari daerah miskin, rawan kemiskinan struktural.
            @elseif($kategori === 'Rawan Kemiskinan Lokal')
                Kepadatan tinggi per dusun, potensi overburden infrastruktur.
            @else
                Stabilitas tinggi, fokus pencegahan.
            @endif
        </p>
    </div>

    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Penanggulangan Kemiskinan</h4>
        <ul>
            @foreach($rekomendasi as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Ngoceh Go</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>