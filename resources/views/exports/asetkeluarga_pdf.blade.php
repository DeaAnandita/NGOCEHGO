<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        th, td {
            border: 1px solid #999;
            padding: 4px 6px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <h2>Data Aset Keluarga - NGOCEH GO</h2>

    @foreach($chunks as $chunkIndex => $chunk)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>No KK</th>
                    <th>Nama Kepala Keluarga</th>
                    @foreach($chunk as $namaAset)
                        <th>{{ $namaAset }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($dataKeluarga as $index => $keluarga)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $keluarga->no_kk }}</td>
                        <td>{{ $keluarga->keluarga->keluarga_kepalakeluarga ?? '-' }}</td>
                        @foreach($chunk as $kode => $namaAset)
                            @php
                                $kolom = "asetkeluarga_{$kode}";
                                $idJawab = $keluarga->$kolom ?? 0;
                            @endphp
                            <td>{{ $jawabanList[$idJawab] ?? 'TIDAK DIISI' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
