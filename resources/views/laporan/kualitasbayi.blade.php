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
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Kualitas Bayi</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Data Bayi Terdata:</strong> {{ number_format($totalData ?? 0) }}</p>
        <p><strong>Desa dengan Kasus Tertinggi:</strong> 
            {{ !empty($desaTertinggi) && $desaTertinggi !== '-' ? $desaTertinggi : 'Tidak ada data' }}
        </p>
        <p><strong>Kategori Risiko Kesehatan Bayi:</strong> {{ $kategori ?? 'Tidak Diketahui' }}</p>
    </div>

    <h3>Distribusi Data Kualitas Bayi</h3>
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Pertanyaan</th>
                <th style="width:80px;">ADA</th>
                <th style="width:100px;">PERNAH ADA</th>
                <th style="width:80px;">TIDAK ADA</th>
                <th style="width:100px;">TIDAK DIISI</th>
            </tr>
        </thead>
        <tbody>
            <tr><td align="center">1</td><td>Keguguran kandungan</td>
                <td align="center">{{ $data['keguguran_ada'] ?? 0 }}</td>
                <td align="center">{{ $data['keguguran_pernah'] ?? 0 }}</td>
                <td align="center">{{ $data['keguguran_tidak'] ?? 0 }}</td>
                <td align="center">{{ $data['keguguran_kosong'] ?? 0 }}</td>
            </tr>

            <tr><td align="center">2</td><td>Bayi lahir hidup normal</td>
                <td align="center">{{ $data['lahir_normal_ada'] ?? 0 }}</td>
                <td align="center">{{ $data['lahir_normal_pernah'] ?? 0 }}</td>
                <td align="center">{{ $data['lahir_normal_tidak'] ?? 0 }}</td>
                <td align="center">{{ $data['lahir_normal_kosong'] ?? 0 }}</td>
            </tr>

            <tr><td align="center">3</td><td>Bayi lahir hidup cacat</td>
                <td align="center">{{ $data['lahir_cacat_ada'] ?? 0 }}</td>
                <td align="center">{{ $data['lahir_cacat_pernah'] ?? 0 }}</td>
                <td align="center">{{ $data['lahir_cacat_tidak'] ?? 0 }}</td>
                <td align="center">{{ $data['lahir_cacat_kosong'] ?? 0 }}</td>
            </tr>

            <tr><td align="center">4</td><td>Bayi lahir mati</td>
                <td align="center">{{ $data['lahir_mati_ada'] ?? 0 }}</td>
                <td align="center">{{ $data['lahir_mati_pernah'] ?? 0 }}</td>
                <td align="center">{{ $data['lahir_mati_tidak'] ?? 0 }}</td>
                <td align="center">{{ $data['lahir_mati_kosong'] ?? 0 }}</td>
            </tr>

            <tr><td align="center">5</td><td>Bayi lahir berat kurang dari 2,5 kg (BBLR)</td>
                <td align="center">{{ $data['bblr_ada'] ?? 0 }}</td>
                <td align="center">{{ $data['bblr_pernah'] ?? 0 }}</td>
                <td align="center">{{ $data['bblr_tidak'] ?? 0 }}</td>
                <td align="center">{{ $data['bblr_kosong'] ?? 0 }}</td>
            </tr>

            <tr><td align="center">6</td><td>Bayi lahir berat lebih dari 4 kg (Makrosomia)</td>
                <td align="center">{{ $data['makrosomia_ada'] ?? 0 }}</td>
                <td align="center">{{ $data['makrosomia_pernah'] ?? 0 }}</td>
                <td align="center">{{ $data['makrosomia_tidak'] ?? 0 }}</td>
                <td align="center">{{ $data['makrosomia_kosong'] ?? 0 }}</td>
            </tr>

            <tr><td align="center">7</td><td>Bayi 0-5 tahun hidup yang menderita kelainan organ</td>
                <td align="center">{{ $data['kelainan_organ_ada'] ?? 0 }}</td>
                <td align="center">{{ $data['kelainan_organ_pernah'] ?? 0 }}</td>
                <td align="center">{{ $data['kelainan_organ_tidak'] ?? 0 }}</td>
                <td align="center">{{ $data['kelainan_organ_kosong'] ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    <h3>Analisis Interpretatif</h3>
    <div class="summary">
        <p>• <strong>Persentase Keguguran Kandungan (ADA + PERNAH ADA):</strong> 
            {{ round(((($data['keguguran_ada'] ?? 0) + ($data['keguguran_pernah'] ?? 0)) / max($totalData ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Persentase Bayi Lahir Mati atau Cacat (ADA + PERNAH ADA):</strong> 
            {{ round(((($data['lahir_mati_ada'] ?? 0) + ($data['lahir_mati_pernah'] ?? 0) + 
                      ($data['lahir_cacat_ada'] ?? 0) + ($data['lahir_cacat_pernah'] ?? 0)) / max($totalData ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Persentase Bayi Lahir Berat Badan Rendah (BBLR < 2,5 kg) atau Makrosomia (> 4 kg) (ADA + PERNAH ADA):</strong> 
            {{ round(((($data['bblr_ada'] ?? 0) + ($data['bblr_pernah'] ?? 0) + 
                      ($data['makrosomia_ada'] ?? 0) + ($data['makrosomia_pernah'] ?? 0)) / max($totalData ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Persentase Bayi 0-5 Tahun dengan Kelainan Organ (ADA + PERNAH ADA):</strong> 
            {{ round(((($data['kelainan_organ_ada'] ?? 0) + ($data['kelainan_organ_pernah'] ?? 0)) / max($totalData ?? 1, 1)) * 100, 2) }}%
        </p>
        <p>• <strong>Interpretasi Umum:</strong> 
            @if(($kategori ?? '') === 'Risiko Tinggi')
                Kondisi kesehatan bayi sangat memprihatinkan dengan indikasi tinggi keguguran, kematian perinatal, kelainan bawaan, dan gangguan pertumbuhan. Diperlukan intervensi kesehatan ibu dan bayi secara intensif.
            @elseif(($kategori ?? '') === 'Risiko Sedang')
                Terdapat risiko sedang pada kesehatan bayi. Perlu penguatan program imunisasi, gizi balita, deteksi dini kelainan, dan edukasi orang tua.
            @else
                Kondisi kesehatan bayi relatif baik, tetap lakukan pemantauan pertumbuhan dan perkembangan secara rutin.
            @endif
        </p>
    </div>

    <div class="rekomendasi">
        <h4>Rekomendasi Kebijakan Pemerintah</h4>
        <ul>
            @forelse($rekomendasi ?? [] as $item)
                <li>{{ $item }}</li>
            @empty
                <li>Tidak ada rekomendasi khusus.</li>
            @endforelse
        </ul>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh <strong>Sistem Pembangunan Keluarga</strong>.</p>
        <p><em>Tanggal Cetak:</em> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>