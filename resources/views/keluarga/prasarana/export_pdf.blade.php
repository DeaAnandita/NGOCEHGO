<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Prasarana</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #e5e7eb; }
        h2 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Data Prasarana Keluarga</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No KK</th>
                <th>Kepala Keluarga</th>
                <th>Status Bangunan</th>
                <th>Jenis Bangunan</th>
                <th>Sumber Air</th>
                <th>Penerangan & Daya</th>
                <th>Fasilitas Sanitasi</th>
                <th>Bahan Bakar & Mata Air</th>
                <th>Luas Lantai (m²)</th>
                <th>Jumlah Kamar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prasaranas as $index => $pras)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pras->no_kk }}</td>
                    <td>{{ $pras->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>
                    <td>{{ $pras->statuspemilikbangunan->statuspemilikbangunan ?? '-' }} / {{ $pras->statuspemiliklahan->statuspemiliklahan ?? '-' }}</td>
                    <td>
                        {{ $pras->jenisfisikbangunan->jenisfisikbangunan ?? '-' }}<br>
                        <small>
                            Lantai: {{ $pras->jenislantaibangunan->jenislantaibangunan ?? '-' }}<br>
                            Dinding: {{ $pras->jenisdindingbangunan->jenisdindingbangunan ?? '-' }}<br>
                            Atap: {{ $pras->jenisatapbangunan->jenisatapbangunan ?? '-' }}
                        </small>
                    </td>
                    <td>
                        {{ $pras->sumberairminum->sumberairminum ?? '-' }}<br>
                        <small>
                            Cara: {{ $pras->caraperolehanair->caraperolehanair ?? '-' }}
                        </small>
                    </td>
                    <td>
                        {{ $pras->sumberpeneranganutama->sumberpeneranganutama ?? '-' }}<br>
                        <small>Daya: {{ $pras->sumberdayaterpasang->sumberdayaterpasang ?? '-' }}</small>
                    </td>
                    <td>
                        BAB: {{ $pras->fasilitastempatbab->fasilitastempatbab ?? '-' }}<br>
                        <small>
                            Tinja: {{ $pras->pembuanganakhirtinja->pembuanganakhirtinja ?? '-' }}<br>
                            Sampah: {{ $pras->carapembuangansampah->carapembuangansampah ?? '-' }}
                        </small>
                    </td>
                    <td>
                        {{ $pras->bahanbakarmemasak->bahanbakarmemasak ?? '-' }}<br>
                        <small>Mata Air: {{ $pras->manfaatmataair->manfaatmataair ?? '-' }}</small>
                    </td>
                    <td>{{ $pras->prasdas_luaslantai ?? '-' }}</td>
                    <td>{{ $pras->prasdas_jumlahkamar ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
