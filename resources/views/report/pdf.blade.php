<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Aset Keluarga</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Data Aset Keluarga</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No KK</th>
                <th>Nama Kepala Keluarga</th>
                <th>Jenis Aset</th>
                <th>Jumlah</th>
                <th>Nilai (Rp)</th>
                <th>Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->keluarga->no_kk ?? '-' }}</td>
                <td>{{ $item->keluarga->nama_kepala_keluarga ?? '-' }}</td>
                <td>{{ $item->jenis_aset }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ number_format($item->nilai, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
