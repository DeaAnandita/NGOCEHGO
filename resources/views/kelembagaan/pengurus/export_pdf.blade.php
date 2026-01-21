<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Pengurus</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #111;
            margin: 0;
        }

        h1 {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* SATU HALAMAN */
        .page {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: repeat(3, 1fr);
            /* 3 baris */
            gap: 10px;
            page-break-after: always;
        }

        /* KARTU */
        .card {
            border: 1px solid #aaa;
            border-radius: 8px;
            padding: 6px;
            height: 85mmmm;
            /* KUNCI TINGGI */
            box-sizing: border-box;
            overflow: hidden;
        }

        /* HEADER KARTU */
        .header {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .small {
            font-size: 8px;
            color: #666;
        }

        .label {
            font-size: 8px;
            color: #666;
        }

        .value {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* FOTO */
        .photo {
            width: 70px;
            height: 90px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #999;
        }

        /* TTD */
        .ttd {
            width: 70px;
            max-height: 35px;
            object-fit: contain;
            border: 1px solid #ccc;
            padding: 2px;
        }
    </style>
</head>

<body>

    <h1>DATA PENGURUS KELEMBAGAAN</h1>

    @foreach ($pengurus->chunk(7) as $chunk)
        <div class="page">
            @foreach ($chunk as $p)
                <div class="card">

                    <div class="header">
                        DATA PENGURUS
                        @if ($periode)
                            <div class="small">
                                Periode {{ $periode->tahun_awal }} – {{ $periode->tahun_akhir }}
                            </div>
                        @endif
                    </div>

                    <table width="100%" cellpadding="3">
                        <tr>
                            <!-- FOTO -->
                            <td width="22%" align="center" valign="top">
                                @if ($p->foto)
                                    <img src="{{ public_path('storage/' . $p->foto) }}" class="photo">
                                @else
                                    <div class="photo"></div>
                                @endif
                            </td>

                            <!-- DATA KIRI -->
                            <td width="26%" valign="top">
                                <div class="label">NIK</div>
                                <div class="value">{{ $p->nomor_induk }}</div>

                                <div class="label">Nama</div>
                                <div class="value">{{ $p->nama_lengkap }}</div>

                                <div class="label">Jenis Kelamin</div>
                                <div class="value">
                                    {{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </div>

                                <div class="label">HP</div>
                                <div class="value">{{ $p->no_hp }}</div>
                            </td>

                            <!-- DATA TENGAH -->
                            <td width="26%" valign="top">
                                <div class="label">Email</div>
                                <div class="value">{{ $p->email }}</div>

                                <div class="label">Unit</div>
                                <div class="value">{{ $p->unit->nama_unit ?? '-' }}</div>

                                <div class="label">Jabatan</div>
                                <div class="value">{{ $p->jabatan->jabatan ?? '-' }}</div>

                                <div class="label">Status</div>
                                <div class="value">{{ $p->status->status_pengurus ?? '-' }}</div>
                            </td>

                            <!-- DATA KANAN + TTD -->
                            <td width="26%" valign="top" align="center">
                                <div class="label">SK</div>
                                <div class="value" style="font-size:8px">{{ $p->no_sk }}</div>

                                <div class="label">Tanggal</div>
                                <div class="value">{{ $p->tanggal_sk }}</div>

                                <div style="margin-top:6px">
                                    @if ($p->tanda_tangan)
                                        <img src="{{ public_path('storage/' . $p->tanda_tangan) }}" class="ttd">
                                    @else
                                        <div class="ttd"></div>
                                    @endif
                                </div>

                                <div class="small">Tanda Tangan</div>
                            </td>
                        </tr>
                    </table>

                </div>
            @endforeach
        </div>
    @endforeach

</body>

</html>
