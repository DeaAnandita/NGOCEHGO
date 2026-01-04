<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ringkasan Data Keluarga</title>
    <style>
        /* style tetap sama seperti sebelumnya */
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 11px; line-height: 1.4; }
        h1, h2, h3, h4 { margin: 5px 0; }
        .header { text-align: center; margin-bottom: 10px; }
        .small { font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table th, table td { border: 1px solid #000; padding: 5px; text-align: left; }
        table th { background: #f2f2f2; }
        .section { margin-top: 12px; page-break-inside: avoid; }
        .note { background: #f9f9f9; border: 1px dashed #aaa; padding: 6px; margin-top: 6px; font-size: 10px; }
        footer { position: fixed; bottom: 0; font-size: 9px; text-align: center; width: 100%; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN RINGKASAN DATA KELUARGA</h2>
        <div class="small">Sistem Pendataan Kependudukan NGOCEH GO</div>
        <hr>
    </div>

    <!-- 1. DATA DASAR -->
    <div class="section">
        <h3>1. Data Dasar</h3>
        <table>
            <tr><th>Total Keluarga</th><td>{{ $ringkasan->total_keluarga }}</td></tr>
            <tr><th>Total Penduduk</th><td>{{ $ringkasan->total_penduduk }}</td></tr>
        </table>
        <div class="note">Ringkasan keseluruhan keluarga dan penduduk yang tercatat dalam sistem.</div>
    </div>

    <!-- 2. RINGKASAN MUTASI -->
    <div class="section">
        <h3>2. Ringkasan Mutasi</h3>
        <table>
            <thead>
                <tr>
                    <th>Jenis Mutasi</th>
                    <th>Jumlah Keluarga</th>
                    <th>Jumlah Penduduk</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mutasiMasuk as $m)
                    <tr>
                        <td>{{ $m->mutasimasuk }}</td>
                        <td>{{ $m->jumlah_keluarga }}</td>
                        <td>{{ $m->jumlah_penduduk }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f2f2f2; font-weight:bold;">
                    <td>Total</td>
                    <td>{{ $mutasiMasuk->sum('jumlah_keluarga') }}</td>
                    <td>{{ $mutasiMasuk->sum('jumlah_penduduk') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="note">
            Tabel ini menunjukkan jumlah Kartu Keluarga (KK) dan individu yang terdampak oleh masing-masing jenis mutasi. 
            Mutasi Lahir biasanya memiliki jumlah keluarga lebih rendah dibandingkan jumlah penduduk karena satu KK bisa memiliki beberapa kelahiran.
        </div>
    </div>

    <!-- 3. REKAP PER DUSUN -->
    <div class="section">
        <h3>3. Rekapitulasi Berdasarkan Dusun / Lingkungan</h3>
        <p><strong>Jumlah Dusun:</strong> {{ $jumlahDusun }}</p>

        <table>
            <thead>
                <tr><th>Dusun</th><th>Total Keluarga</th><th>Total Penduduk</th></tr>
            </thead>
            <tbody>
                @foreach ($perDusun as $d)
                    <tr>
                        <td>{{ $d->dusun }}</td>
                        <td>{{ $d->total_keluarga }}</td>
                        <td>{{ $d->total_penduduk }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($dusunTerpadat)
            <div class="note">
                <strong>Rekomendasi:</strong> Dusun <strong>{{ $dusunTerpadat->dusun }}</strong> memiliki jumlah penduduk terbanyak 
                ({{ $dusunTerpadat->total_penduduk }} jiwa dari {{ $dusunTerpadat->total_keluarga }} KK). 
                Wilayah ini direkomendasikan sebagai prioritas untuk program penanggulangan kemiskinan, 
                pemantauan sosial, dan distribusi bantuan.
            </div>
        @endif
    </div>

    <!-- 4. REKAPITULASI BERDASARKAN RW / RT -->
<div class="section">
    <h3>4. Rekapitulasi Data Keluarga per RW dan RT</h3>
    <table>
        <thead>
            <tr>
                <th style="width:15%;">RW</th>
                <th style="width:15%;">RT</th>
                <th style="width:20%; text-align:center;">Jumlah Keluarga</th>
                <th style="width:20%; text-align:center;">Jumlah Penduduk</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapRwRt as $rw => $rts)
                @php
                    $subtotalKeluarga = $rts->sum('total_keluarga');
                    $subtotalPenduduk = $rts->sum('total_penduduk');
                    $jumlahRtRw = $rts->count();
                @endphp

                @foreach($rts as $index => $item)
                    <tr>
                        @if($index == 0)
                            <td rowspan="{{ $jumlahRtRw }}">{{ $rw }}</td>
                        @endif
                        <td>{{ $item->rt }}</td>
                        <td style="text-align:center;">{{ $item->total_keluarga }}</td>
                        <td style="text-align:center;">{{ $item->total_penduduk }}</td>
                    </tr>
                @endforeach

                <!-- Subtotal per RW -->
                <tr style="background:#e6e6e6; font-weight:bold;">
                    <td colspan="2" style="text-align:center;">Subtotal RW {{ $rw }}, jumlah RT: {{ $jumlahRtRw }}</td>
                    <td style="text-align:center;">{{ $subtotalKeluarga }} Keluarga</td>
                    <td style="text-align:center;">{{ $subtotalPenduduk }} Penduduk</td>
                </tr>
            @endforeach

            <!-- Garis pemisah -->
            <tr>
                <td colspan="4" style="border-top:2px solid #000; padding:4px 0;"></td>
            </tr>

            <!-- Total Keseluruhan -->
            <tr style="background:#d0d0d0; font-weight:bold; font-size:12px;">
                <td colspan="2" style="text-align:center;">TOTAL: {{ $jumlahRw }} RW, {{ $jumlahRt }} RT</td>
                <td style="text-align:center;">{{ $totalKeluargaKeseluruhan }} KK</td>
                <td style="text-align:center;">{{ $totalPendudukKeseluruhan }} Jiwa</td>
            </tr>
        </tbody>
    </table>

    <div class="note">
        Tabel ini menampilkan distribusi keluarga dan penduduk secara hierarkis per RW dan RT, 
        dilengkapi subtotal per RW serta total keseluruhan desa.
    </div>
</div>

    <!-- 5. ANALISIS & INTERPRETASI -->
    <div class="section">
        <h3>5. Analisis & Interpretasi</h3>
        @if(count($catatan))
            <div class="note" style="background:#f0f8ff; border-left:4px solid #0066cc; padding:10px;">
                <ul style="margin:0; padding-left:20px;">
                    @foreach($catatan as $c)
                        <li>{{ $c }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <p>Tidak ada catatan analisis otomatis yang signifikan.</p>
        @endif

        <div class="note">
            Analisis ini bersifat dinamis dan mengikuti data terkini. Untuk perhitungan kepadatan penduduk yang lebih presisi (jiwa/km² atau hektar), integrasikan data luas wilayah dari tabel master_dusun. Rekomendasi lanjutan bisa melibatkan data eksternal seperti DTKS (Data Terpadu Kesejahteraan Sosial).
        </div>
    </div>

    <!-- 6. REKOMENDASI INTERVENSI PEMERINTAH -->
    <div class="section">
        <h3>6. Rekomendasi Intervensi Penanggulangan Kemiskinan</h3>
        <ul>
            @foreach($rekomendasiIntervensi as $r)
                <li>{{ $r }}</li>
            @endforeach
        </ul>
        <div class="note">
            Rekomendasi di atas disusun berdasarkan data kepadatan keluarga dan penduduk saat ini. 
            Untuk akurasi lebih tinggi, integrasikan dengan data luas wilayah (kepadatan jiwa/km²), 
            status ekonomi, dan indikator kemiskinan lainnya.
        </div>
    </div>

    <footer>
        Dicetak secara otomatis melalui sistem — {{ now()->format('d/m/Y') }}
    </footer>

</body>
</html>