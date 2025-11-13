<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px 25px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 12px; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 13px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #d1d5db; padding: 6px; font-size: 11px; vertical-align: top; }
        th { background: #f3f4f6; text-align: center; font-weight: bold; }
        tr:nth-child(even) { background: #fafafa; }
        .summary { border: 1px solid #d1d5db; padding: 10px; background: #f9fafb; border-radius: 5px; margin-bottom: 12px; }
        .rekomendasi { border: 1px solid #d1d5db; background: #f0fdf4; padding: 10px; border-radius: 5px; margin-top: 10px; }
        .rekomendasi h4 { margin: 0 0 6px; font-size: 13px; color: #166534; }
        .footer { margin-top: 20px; font-size: 11px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Analisis Bangun Keluarga</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Keluarga Terdata:</strong> {{ number_format($data->count()) }}</p>
        <p><strong>Skor Rata-rata Bangun Keluarga:</strong> {{ $skor }} / 100</p>
        <p><strong>Kategori:</strong> {{ $kategori }}</p>
    </div>

    <h3>Rata-rata Indikator Bangun Keluarga (1–51)</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Indikator</th>
                <th style="width:120px;">Nilai Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @foreach(range(1,51) as $i)
                <tr>
                    <td align="center">{{ $i }}</td>
                    <td>{{ $master->where('kdpembangunankeluarga',$i)->first()->pembangunankeluarga ?? 'Indikator '.$i }}</td>
                    <td align="center">{{ number_format($indikator['indikator_'.$i] ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Pemerintah Desa</h4>
        <ul>
            @foreach($rekomendasi as $r)
                <li>{{ $r }}</li>
            @endforeach
        </ul>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Pembangunan Keluarga</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>
