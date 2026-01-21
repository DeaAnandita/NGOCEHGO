<!DOCTYPE html>
<html>

<head>
    <title>Buku Tanah Desa</title>

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

        /* FOTO BESAR */
        .foto {
            width: 210px;
            height: 150px;
            object-fit: cover;
            border: 1px solid #999;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="title">BUKU TANAH DESA</div>

    @if ($search)
        <p>Filter: <b>{{ $search }}</b></p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th style="width:220px">Foto</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Jenis Pemilik</th>
                <th>Pemilik</th>
                <th>Kode Pemilik</th>
                <th>Luas</th>
                <th>Status Hak</th>
                <th>Penggunaan</th>
                <th>Mutasi</th>
                <th>Tgl Mutasi</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>

                    <td class="center" style="width:220px">
                        @if ($row->fototanahdesa && file_exists(public_path('storage/' . $row->fototanahdesa)))
                            <img src="{{ public_path('storage/' . $row->fototanahdesa) }}" class="foto">
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $row->kdtanahdesa }}</td>
                    <td class="center">{{ $row->tanggaltanahdesa }}</td>
                    <td>{{ $row->jenisPemilik->jenispemilik ?? '-' }}</td>
                    <td>{{ $row->pemiliktanahdesa }}</td>
                    <td>{{ $row->kdpemilik }}</td>
                    <td class="center">{{ $row->luastanahdesa }}</td>
                    <td>{{ $row->statusHak->statushaktanah ?? '-' }}</td>
                    <td>{{ $row->penggunaan->penggunaantanah ?? '-' }}</td>
                    <td>{{ $row->mutasi->mutasitanah ?? '-' }}</td>
                    <td class="center">{{ $row->tanggalmutasitanahdesa }}</td>
                    <td>{{ $row->keterangantanahdesa }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
