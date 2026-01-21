<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px
        }

        th {
            background: #eee
        }

        img {
            width: 150px;
            height: 120px;
            object-fit: cover
        }

        tr {
            page-break-inside: avoid
        }
    </style>
</head>

<body>

    <h3 align="center">BUKU INVENTARIS</h3>
    @if ($search)
        <p>Filter: <b>{{ $search }}</b></p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Pengguna</th>
                <th>Identitas</th>
                <th>Volume</th>
                <th>Satuan</th>
                <th>Asal</th>
                <th>Harga</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        @if ($d->inventaris_foto)
                            <img src="{{ public_path('storage/' . $d->inventaris_foto) }}">
                        @endif
                    </td>
                    <td>{{ $d->kdinventaris }}</td>
                    <td>{{ $d->inventaris_tanggal }}</td>
                    <td>{{ $d->pengguna->pengguna ?? '-' }}</td>
                    <td>{{ $d->inventaris_identitas }}</td>
                    <td>{{ $d->inventaris_volume }}</td>
                    <td>{{ $d->satuan->satuanbarang ?? '-' }}</td>
                    <td>{{ $d->asalBarang->asalbarang ?? '-' }}</td>
                    <td>{{ $d->inventaris_harga }}</td>
                    <td>{{ $d->inventaris_keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
