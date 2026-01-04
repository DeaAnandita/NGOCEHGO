<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Prasarana Dasar</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 11px; line-height: 1.4; margin: 0; padding: 0; }
        h1, h2, h3, h4 { margin: 5px 0; }
        .header { text-align: center; margin: 10px 0 5px 0; }
        .small { font-size: 10px; color: #555; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table th, table td { border: 1px solid #000; padding: 4px; text-align: left; vertical-align: top; }
        table th { background: #f2f2f2; }
        .section { margin-top: 10px; page-break-inside: avoid; }
        .note { background: #f9f9f9; border: 1px dashed #aaa; padding: 5px; margin-top: 5px; font-size: 10px; }
        .highlight { background: #fff3cd; font-weight: bold; }
        footer { position: fixed; bottom: 0; font-size: 9px; text-align: center; width: 100%; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN ANALISIS PRASARANA DASAR</h2>
        <h3>Indikator Kesejahteraan Objektif Prasarana Dasar</h3>
        <div class="small">Sistem Pendataan Kependudukan NGOCEH GO</div>
        <hr style="margin: 5px 0;">
    </div>

    <!-- 1. RINGKASAN UMUM -->
    <div class="section">
        <h3>1. Ringkasan Umum</h3>
        <table>
            <tr><th>Total Keluarga dengan Data Prasarana Tercatat</th><td>{{ number_format($totalKeluargaTerdata) }} KK</td></tr>
        </table>
    </div>

    <!-- 2. PROFIL LEGALITAS ASET -->
    <div class="section">
        <h3>2. Profil Legalitas Kepemilikan Bangunan dan Lahan</h3>
        <p>Ringkasan status kepemilikan utama (kombinasi dengan ≥10 KK):</p>
        <table>
            <thead>
                <tr><th>Status Pemilik Bangunan</th><th>Status Pemilik Lahan</th><th>Jumlah KK</th></tr>
            </thead>
            <tbody>
                @foreach($profilLegalitas as $item)
                    <tr>
                        <td>{{ $item->status_bangunan }}</td>
                        <td>{{ $item->status_lahan }}</td>
                        <td style="text-align:center;">{{ number_format($item->jumlah_kk) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="note">
            Mayoritas keluarga memiliki bangunan dan lahan milik sendiri. Prioritaskan PTSL untuk kelompok rentan (sewa/pinjam pakai).
        </div>
    </div>

    <!-- 3. TEMUAN KUNCI INDIKATOR KEMISKINAN FISIK -->
    <div class="section">
        <h3>3. Temuan Kunci Indikator Kemiskinan Fisik</h3>
        <table>
            <thead>
                <tr>
                    <th>Indikator</th>
                    <th>Jumlah KK</th>
                    <th>Keterangan & Rekomendasi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="highlight">
                    <td>Material bangunan rendah (tanah/bambu/jerami)</td>
                    <td>{{ number_format($temuanKunci['material_rendah']) }} KK</td>
                    <td>Indikator ini menunjukkan rumah tidak layak huni (RTLH). Rekomendasi: Prioritaskan program Bedah Rumah (BSPS/Rutilahu) untuk perbaikan struktural, terintegrasi dengan P3KE untuk kemiskinan ekstrem.</td>
                </tr>
                <tr class="highlight">
                    <td>Lantai/dinding/atap dalam kondisi jelek</td>
                    <td>{{ number_format($temuanKunci['kondisi_jelek']) }} KK</td>
                    <td>Menandakan kerentanan terhadap bencana alam dan kesehatan. Rekomendasi: Verifikasi lapangan dan bantuan renovasi melalui dana desa atau BSPS, fokus pada kelompok rentan seperti lansia dan anak-anak.</td>
                </tr>
                <tr class="highlight">
                    <td>Sanitasi tidak layak (jamban umum/buang ke lingkungan)</td>
                    <td>{{ number_format($temuanKunci['sanitasi_buruk']) }} KK</td>
                    <td>Berkaitan dengan risiko stunting dan penyakit. Rekomendasi: Program jambanisasi dan tangki septik komunal, koordinasi dengan Dinas Kesehatan untuk pencegahan stunting nasional 2026.</td>
                </tr>
                <tr class="highlight">
                    <td>Masih menggunakan kayu bakar/minyak tanah</td>
                    <td>{{ number_format($temuanKunci['bahan_bakar_trad']) }} KK</td>
                    <td>Meningkatkan polusi rumah tangga dan beban ekonomi. Rekomendasi: Konversi ke LPG/kompor listrik melalui subsidi tepat sasaran, integrasi dengan program energi bersih 2026.</td>
                </tr>
                <tr>
                    <td>Kepadatan hunian di bawah standar (< 8 m²/orang)</td>
                    <td>{{ number_format($temuanKunci['luas_bawah_standar']) }} KK</td>
                    <td>Menyebabkan masalah sosial dan kesehatan. Rekomendasi: Bantuan penambahan ruang hunian atau relokasi jika lahan terbatas, sesuai standar BPS untuk hunian sehat.</td>
                </tr>
                <tr>
                    <td>Daya listrik rendah (≤450 VA atau tanpa meteran)</td>
                    <td>{{ number_format($energi->daya_rendah ?? 0) }} KK</td>
                    <td>Membatasi akses teknologi dan produktivitas. Rekomendasi: Upgrade daya listrik melalui PLN subsidi, prioritas untuk KK dengan usaha mikro di rumah.</td>
                </tr>
            </tbody>
        </table>
        <div class="note">
            Temuan ini merupakan bukti fisik kemiskinan yang objektif. Gunakan untuk validasi DTKS dan pensasaran program kemiskinan 2026.
        </div>
    </div>

    <!-- 4. ESTIMASI KATEGORISASI EKONOMI RUMAH TANGGA (DESIL) -->
    <div class="section">
        <h3>4. Estimasi Kategorisasi Ekonomi Rumah Tangga (Desil)</h3>
        <table>
            <thead>
                <tr>
                    <th>Desil</th>
                    <th>Jumlah KK</th>
                    <th>Keterangan & Rekomendasi Intervensi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="highlight">
                    <td>Desil 1 & 2 (Kemiskinan Ekstrem – Prioritas P3KE)</td>
                    <td>{{ number_format($desil12) }} KK</td>
                    <td>KK dengan multiple indikator berat (material rendah, kondisi jelek, dll.). Rekomendasi: Verifikasi segera untuk masuk P3KE, bantuan langsung seperti BLT Ekstrem, Bedah Rumah, dan jambanisasi. Koordinasi dengan Kemensos untuk akses DTKS terbaru 2026.</td>
                </tr>
                <tr>
                    <td>Desil 3 – 7 (Menengah & Rentan)</td>
                    <td>{{ number_format($desil37) }} KK</td>
                    <td>KK dengan prasarana cukup, tapi rentan jatuh miskin (misalnya kepadatan tinggi). Rekomendasi: Program pencegahan seperti PTSL (sertifikasi tanah), pelatihan usaha mikro, dan monitoring rutin untuk cegah kemiskinan baru. Integrasikan dengan BPNT atau PKH jika diperlukan.</td>
                </tr>
                <tr>
                    <td>Desil 8 – 10 (Ekonomi Mapan)</td>
                    <td>{{ number_format($desil810) }} KK</td>
                    <td>KK dengan prasarana premium (lantai marmer, daya tinggi, dll.). Rekomendasi: Dikecualikan dari bantuan sosial, tapi libatkan dalam program CSR desa atau gotong royong untuk bantu kelompok rendah. Pantau untuk redistribusi pajak/insentif ekonomi.</td>
                </tr>
            </tbody>
        </table>
        <div class="note">
            Estimasi desil berdasarkan indikator prasarana objektif. Untuk akurasi, padukan dengan data pendapatan DTKS dan verifikasi lapangan.
        </div>
    </div>

    <!-- 5. ANALISIS & INTERPRETASI -->
    <div class="section">
        <h3>5. Analisis & Interpretasi</h3>
        @if(count($catatan))
            <div class="note" style="background:#f0f8ff; border-left:4px solid #0066cc; padding:10px;">
                @foreach($catatan as $c)
                    <p>{!! $c !!}</p>
                @endforeach
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
            @foreach($rekomendasi as $r)
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