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
        table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        table-layout: fixed; /* PENTING: membuat kolom proporsional dan mencegah overflow */
    }
    th, td {
        border: 1px solid #d1d5db;
        padding: 6px 8px; /* sedikit kurangi padding agar lebih muat */
        font-size: 11px; /* turunkan sedikit dari 12px agar lebih ringkas */
        vertical-align: top;
        word-wrap: break-word; /* untuk browser */
        overflow-wrap: break-word; /* standar modern */
        hyphens: auto; /* opsional: hyphenation untuk pemotongan kata */
    }
    th {
        background-color: #f3f4f6;
        text-align: center;
        font-weight: bold;
        font-size: 11px;
    }
    /* Atur proporsi lebar kolom secara eksplisit */
    th:nth-child(1), td:nth-child(1) { width: 5px; }   /* No */
    th:nth-child(2), td:nth-child(2) { width: 20%; }  /* Nama Aset Lahan */
    th:nth-child(3), td:nth-child(3) { width: 16%; }  /* Jumlah Memiliki */
    th:nth-child(4), td:nth-child(4) { width: 16%; }  /* Total Luas */
    th:nth-child(5), td:nth-child(5) { width: 16%; }  /* Jumlah Tidak Memiliki */
    th:nth-child(6), td:nth-child(6) { width: 18%; }  /* Keterangan (kurangi dari 200px) */

    tr:nth-child(even) { background: #fafafa; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Analisis Aset Lahan Keluarga</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Keluarga Terdata:</strong> {{ number_format($data->count()) }}</p>
        <p><strong>Skor Kepemilikan Lahan Rata-rata:</strong> {{ $skor }} / 100</p>
        <p><strong>Rata-rata Luas Lahan per Keluarga:</strong> {{ $avg_total_luas_raw }} HA</p>
        <div class="category-box">
            Kategori Keluarga: {{ $kategori }}
        </div>
    </div>

    <h3>Analisis Kepemilikan Lahan per Indikator</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Aset Lahan</th>
                <th style="width:120px;">Jumlah Keluarga Memiliki (>0 HA)</th>
                <th style="width:120px;">Total Luas (HA)</th>
                <th style="width:120px;">Jumlah Keluarga Tidak Memiliki</th>
                <th style="width:200px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($master as $item)
                @php
                    $kode = $item->kdasetlahan;
                    $info = $indikator["aset_$kode"] ?? ['count_memiliki' => 0, 'total_luas' => 0, 'count_tidak' => 0, 'keterangan' => 'Data tidak tersedia.'];
                @endphp
                <tr>
                    <td align="center">{{ $kode }}</td>
                    <td>{{ $item->asetlahan }}</td>
                    <td align="center">{{ $info['count_memiliki'] }}</td>
                    <td align="center">{{ $info['total_luas'] }}</td>
                    <td align="center">{{ $info['count_tidak'] }}</td>
                    <td>{{ $info['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>
    <h3>Ringkasan Kategori Lahan</h3>
    <div class="summary">
        <p>• <strong>Lahan Produktif (pertanian, perkebunan, dll):</strong> Rata-rata {{ $avg_persen_produktif }}% keluarga memiliki ({{ round(($data->count() * $avg_persen_produktif / 100), 0) }} keluarga)</p>
        <p>• <strong>Lahan Non-Produktif (sewa, pinjam, dll):</strong> Rata-rata {{ $avg_persen_nonproduktif }}% keluarga memiliki ({{ round(($data->count() * $avg_persen_nonproduktif / 100), 0) }} keluarga)</p>
        <p>• <strong>Rata-rata total luas lahan per keluarga:</strong> {{ $avg_total_luas }} HA (presisi: {{ $avg_total_luas_raw }} HA)</p>
    </div>

    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>{{ $analisis }}</p>
    </div>

    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Pemerintah dalam Penanggulangan Kemiskinan</h4>
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