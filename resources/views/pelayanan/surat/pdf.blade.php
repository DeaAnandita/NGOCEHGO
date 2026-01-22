<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <style>
        /* ===== SETUP DOMPDF ===== */
        @page {
            size: A4;
            margin: 2.2cm 2.2cm 2.2cm 2.2cm;
            /* atas kanan bawah kiri */
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            color: #000;
        }

        .page {
            width: 100%;
        }

        /* ===== KOP ===== */
        .kop-wrap {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-table td {
            border: none;
            vertical-align: middle;
        }

        .kop-logo img {
            display: block;
            margin: 0 auto;
        }

        .kop-text {
            text-align: center;
            line-height: 1.2;
        }

        .kop-text .l1 {
            font-weight: bold;
            font-size: 14pt;
            letter-spacing: .2px;
        }

        .kop-text .l2 {
            font-weight: bold;
            font-size: 13pt;
            letter-spacing: .2px;
        }

        .kop-text .l3 {
            font-weight: bold;
            font-size: 13pt;
            letter-spacing: .2px;
        }

        /* opsional: alamat kantor di kop (kalau mau) */
        .kop-text .alamat {
            font-size: 10.5pt;
            margin-top: 4px;
            line-height: 1.25;
        }

        /* ===== JUDUL ===== */
        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 8px 0 2px 0;
            letter-spacing: .3px;
        }

        .nomor {
            text-align: center;
            margin: 0 0 18px 0;
        }

        /* ===== PARAGRAF ===== */
        p {
            margin: 0 0 10px 0;
            line-height: 1.5;
            text-align: justify;
        }

        .indent {
            text-indent: 1.1cm;
        }

        /* ===== TABEL DATA ===== */
        .data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 10px;
        }

        .data td {
            border: none;
            padding: 2px 0;
            vertical-align: top;
        }

        .data .lbl {
            width: 33%;
        }

        .data .sep {
            width: 3%;
        }

        .data .val {
            width: 64%;
        }

        /* ===== TTD ===== */
        .ttd {
            width: 100%;
            border-collapse: collapse;
            margin-top: 28px;
        }

        .ttd td {
            border: none;
            vertical-align: top;
        }

        .ttd .kiri,
        .ttd .kanan {
            width: 50%;
        }

        .ttd .kiri {
            text-align: center;
        }

        .ttd .kanan {
            text-align: center;
        }

        .spasi-ttd {
            height: 70px;
            /* ruang tanda tangan / stempel */
        }

        .nama-underline {
            display: inline-block;
            border-bottom: 1px solid #000;
            padding-bottom: 1px;
            font-weight: bold;
        }

        .kode-verif {
            font-size: 9.5pt;
            margin-top: 6px;
            line-height: 1.2;
        }

        /* kecilin QR biar natural */
        .qr {
            margin-top: 6px;
        }

        /* ===== GARIS TIPIS (opsional gaya surat) ===== */
        .garis-tipis {
            border-top: 1px solid #000;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    @php
        $tanggalSurat = $surat->tanggal_surat ?? ($surat->approved_at ?? $surat->created_at);
        $tanggalSurat = $tanggalSurat ? \Carbon\Carbon::parse($tanggalSurat) : null;
    @endphp

    <div class="page">

        <!-- ===== KOP SURAT ===== -->
        <div class="kop-wrap">
            <table class="kop-table">
                <tr>
                    <td class="kop-logo" style="width: 15%; text-align:center;">
                        <img src="{{ public_path('images/logo-kudus.png') }}" width="85" alt="Logo">
                    </td>
                    <td class="kop-text" style="width: 85%;">
                        <div class="l1">PEMERINTAH DESA KALIWUNGU</div>
                        <div class="l2">KECAMATAN KALIWUNGU</div>
                        <div class="l3">KABUPATEN KUDUS</div>

                        {{-- Opsional alamat kantor (kalau mau terlihat lebih “asli”)
                    <div class="alamat">
                        Alamat: Desa Kaliwungu, Kec. Kaliwungu, Kab. Kudus, Jawa Tengah<br>
                        Kode Pos: 59332
                    </div>
                    --}}
                    </td>
                </tr>
            </table>
            <div class="garis-tipis"></div>
        </div>

        <!-- ===== JUDUL ===== -->
        <div class="judul">SURAT KETERANGAN / PENGANTAR</div>
        <div class="nomor">Nomor : {{ $surat->nomor_surat }}</div>

        <!-- ===== ISI ===== -->
        <p class="indent">
            Yang bertanda tangan di bawah ini Kepala Desa Kaliwungu Kecamatan Kaliwungu Kabupaten Kudus menerangkan
            bahwa:
        </p>

        <table class="data">
            <tr>
                <td class="lbl">Nama</td>
                <td class="sep">:</td>
                <td class="val">{{ strtoupper($surat->nama) }}</td>
            </tr>
            <tr>
                <td class="lbl">Tempat, Tgl Lahir</td>
                <td class="sep">:</td>
                <td class="val">{{ $surat->tempat_lahir }}, {{ $surat->tanggal_lahir?->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="lbl">Jenis Kelamin</td>
                <td class="sep">:</td>
                <td class="val">{{ $surat->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td class="lbl">Kewarganegaraan</td>
                <td class="sep">:</td>
                <td class="val">{{ $surat->kewarganegaraan }}</td>
            </tr>
            <tr>
                <td class="lbl">Agama</td>
                <td class="sep">:</td>
                <td class="val">{{ $surat->agama }}</td>
            </tr>
            <tr>
                <td class="lbl">Pekerjaan</td>
                <td class="sep">:</td>
                <td class="val">{{ $surat->pekerjaan }}</td>
            </tr>
            <tr>
                <td class="lbl">NIK</td>
                <td class="sep">:</td>
                <td class="val">{{ $surat->nik }}</td>
            </tr>
            <tr>
                <td class="lbl">Alamat</td>
                <td class="sep">:</td>
                <td class="val">{{ $surat->alamat }}</td>
            </tr>
            <tr>
                <td class="lbl">Keperluan</td>
                <td class="sep">:</td>
                <td class="val">{{ $surat->keperluan }}</td>
            </tr>
            <tr>
                <td class="lbl">Tanggal Surat</td>
                <td class="sep">:</td>
                <td class="val">{{ $tanggalSurat?->translatedFormat('d F Y') }}</td>
            </tr>

            @if (!empty($surat->keterangan_lain))
                <tr>
                    <td class="lbl">Keterangan Lain</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $surat->keterangan_lain }}</td>
                </tr>
            @endif
        </table>

        <p class="indent">
            Demikian surat ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
        </p>

        <!-- ===== TANDA TANGAN ===== -->
        <table class="ttd">
            <tr>
                <!-- KIRI: PEMEGANG -->
                <td class="kiri">
                    <div>Yang Bersangkutan,</div>
                    <div class="spasi-ttd"></div>
                    <div class="nama-underline">{{ strtoupper($surat->nama) }}</div>
                </td>

                <!-- KANAN: KADES -->
                <td class="kanan">
                    <div>Kaliwungu, {{ $tanggalSurat?->translatedFormat('d F Y') }}</div>
                    <div>Kepala Desa Kaliwungu</div>

                    <div class="spasi-ttd" style="height: 20px;"></div>

                    @if ($surat->barcode_verifikasi_path)
                        <div class="qr">
                            <img src="{{ public_path($surat->barcode_verifikasi_path) }}" width="85"
                                alt="QR">
                        </div>
                    @endif

                    {{-- ruang tanda tangan basah / stempel (tetap sisakan ruang) --}}
                    <div></div>
                    @if (!empty($surat->nip_kepala_desa))
                        <div style="font-size: 11pt; margin-top: 2px;">
                            NIP. {{ $surat->nip_kepala_desa }}
                        </div>
                    @endif
                    @if ($surat->kode_verifikasi)
                        <div class="kode-verif">
                            Kode Verifikasi: {{ $surat->kode_verifikasi }}
                        </div>
                    @endif
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
