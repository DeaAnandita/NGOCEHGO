<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Aparat Desa</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #111;
        }

        h1 {
            text-align: center;
            font-size: 14px;
            margin: 0 0 10px 0;
            letter-spacing: .5px;
        }

        .card {
            border: 1px solid #aaa;
            border-radius: 10px;
            padding: 8px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .header {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .small {
            font-size: 8px;
            color: #666;
        }

        .label {
            font-size: 8px;
            color: #666;
            margin-bottom: 1px;
        }

        .value {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .photo {
            width: 70px;
            height: 90px;
            border-radius: 6px;
            border: 1px solid #999;
            background: #f3f4f6;
        }

        img.photo {
            display: block;
            object-fit: cover;
        }

        .keterangan {
            font-size: 8px;
            font-weight: normal;
            line-height: 1.2;
            word-wrap: break-word;
            overflow: hidden;
            max-height: 90px;
            /* biar card tetap rapi */
        }

        table {
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
        }
    </style>
</head>

<body>

    <h1>DATA APARAT DESA</h1>

    @foreach ($data as $a)
        @php
            $fotoPath = $a->fotopengangkatan ? public_path('storage/' . $a->fotopengangkatan) : null;

            $fotoAda = $fotoPath && file_exists($fotoPath);

            // format tanggal aman
            $tgl = '-';
            if (!empty($a->tanggalpengangkatan)) {
                try {
                    $tgl = \Carbon\Carbon::parse($a->tanggalpengangkatan)->format('d-m-Y');
                } catch (\Exception $e) {
                    $tgl = $a->tanggalpengangkatan; // fallback raw
                }
            }

            $namaAparat = $a->masterAparat->aparat ?? ($a->namaaparat ?? '-');
        @endphp

        <div class="card">
            <div class="header">
                APARAT DESA
                <div class="small">Pemerintah Desa</div>
            </div>

            <table width="100%" cellpadding="4">
                <tr>
                    {{-- FOTO --}}
                    <td width="15%" align="center" valign="top">
                        @if ($fotoAda)
                            <img src="{{ $fotoPath }}" class="photo" alt="foto">
                        @else
                            <div class="photo"></div>
                        @endif
                    </td>

                    {{-- DATA KIRI --}}
                    <td width="35%" valign="top">
                        <div class="label">Nama</div>
                        <div class="value">{{ $namaAparat }}</div>

                        <div class="label">NIP</div>
                        <div class="value">{{ $a->nipaparat ?: '-' }}</div>

                        <div class="label">NIK</div>
                        <div class="value">{{ $a->nik ?: '-' }}</div>

                        <div class="label">Pangkat</div>
                        <div class="value">{{ $a->pangkataparat ?: '-' }}</div>
                    </td>

                    {{-- DATA TENGAH --}}
                    <td width="25%" valign="top">
                        <div class="label">Nomor SK</div>
                        <div class="value">{{ $a->nomorpengangkatan ?: '-' }}</div>

                        <div class="label">Tanggal SK</div>
                        <div class="value">{{ $tgl }}</div>

                        <div class="label">Status</div>
                        <div class="value">{{ $a->statusaparatdesa ?: '-' }}</div>
                    </td>

                    {{-- KANAN --}}
                    <td width="25%" valign="top">
                        <div class="label">Keterangan</div>
                        <div class="keterangan">
                            {{ $a->keteranganaparatdesa ?: '-' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

</body>

</html>
