<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
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

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>REKAP BUKU KEPUTUSAN DESA</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Judul</th>
                <th>Uraian</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $d->jenisKeputusan->jeniskeputusan_umum ?? '-' }}</td>
                    <td>{{ $d->nomor_keputusan }}</td>
                    <td>{{ $d->tanggal_keputusan }}</td>
                    <td>{{ $d->judul_keputusan }}</td>
                    <td>{{ $d->uraian_keputusan }}</td>
                    <td>{{ $d->keterangan_keputusan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
