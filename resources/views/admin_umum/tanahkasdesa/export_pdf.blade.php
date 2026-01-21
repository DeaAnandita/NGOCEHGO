<!DOCTYPE html>
<html>

<head>
    <title>Buku Tanah Kas Desa</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px;
            vertical-align: middle;
        }

        th {
            background: #eee;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* FOTO 3x LEBIH BESAR */
        .foto {
            width: 210px;
            /* 3x 70 */
            height: 150px;
            /* 3x 50 */
            object-fit: cover;
            border: 1px solid #999;
        }

        /* Supaya baris tidak pecah di tengah halaman */
        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="title">BUKU TANAH KAS DESA</div>

    @if ($search)
        <p>Filter: <b>{{ $search }}</b></p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th style="width:220px">Foto</th>
                <th>Kode</th>
                <th>Asal</th>
                <th>Sertifikat</th>
                <th>Luas</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Perolehan</th>
                <th>Jenis</th>
                <th>Patok</th>
                <th>Papan</th>
                <th>Lokasi</th>
                <th>Peruntukan</th>
                <th>Mutasi</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>

                    <td class="center" style="width:220px">
                        @if ($row->fototanahkasdesa && file_exists(public_path('storage/' . $row->fototanahkasdesa)))
                            <img src="{{ public_path('storage/' . $row->fototanahkasdesa) }}" class="foto">
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $row->kdtanahkasdesa }}</td>
                    <td>{{ $row->asaltanahkasdesa }}</td>
                    <td>{{ $row->sertifikattanahkasdesa }}</td>
                    <td class="center">{{ $row->luastanahkasdesa }}</td>
                    <td class="center">{{ $row->kelastanahkasdesa }}</td>
                    <td class="center">{{ $row->tanggaltanahkasdesa }}</td>
                    <td>{{ $row->perolehan->perolehantkd ?? '-' }}</td>
                    <td>{{ $row->jenis->jenistkd ?? '-' }}</td>
                    <td>{{ $row->patok->patok ?? '-' }}</td>
                    <td>{{ $row->papanNama->papannama ?? '-' }}</td>
                    <td>{{ $row->lokasitanahkasdesa }}</td>
                    <td>{{ $row->peruntukantanahkasdesa }}</td>
                    <td>{{ $row->mutasitanahkasdesa }}</td>
                    <td>{{ $row->keterangantanahkasdesa }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
