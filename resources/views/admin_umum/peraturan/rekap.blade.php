<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>

    <h3 style="text-align:center">REKAP BUKU PERATURAN DESA</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Nomor</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $d->jenisPeraturanDesa->jenisperaturandesa ?? '-' }}</td>
                    <td>{{ $d->nomorperaturan }}</td>
                    <td>{{ $d->judulpengaturan }}</td>
                    <td>{{ $d->inputtime }}</td>
                    <td>{{ $d->userinput }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
