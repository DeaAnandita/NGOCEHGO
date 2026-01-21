<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keputusan</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        th {
            background: #eee;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="title">LAPORAN KEPUTUSAN LEMBAGA</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No SK</th>
                <th>Judul</th>
                <th>Jenis</th>
                <th>Unit</th>
                <th>Periode</th>
                <th>Jabatan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($keputusan as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $k->nomor_sk }}</td>
                    <td>{{ $k->judul_keputusan }}</td>
                    <td>{{ $k->jenis->jenis_keputusan ?? '-' }}</td>
                    <td>{{ $k->unit->unit_keputusan ?? '-' }}</td>
                    <td>{{ $k->periode->tahun_awal ?? '-' }}</td>
                    <td>{{ $k->jabatan->jabatan ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($k->tanggal_keputusan)->format('d-m-Y') }}</td>
                    <td>{{ $k->status->status_keputusan ?? '-' }}</td>
                    <td>{{ $k->metode->metode ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
