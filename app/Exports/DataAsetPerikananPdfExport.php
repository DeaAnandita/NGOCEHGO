<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\DataAsetPerikanan;
use App\Models\MasterAsetPerikanan;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class DataAsetPerikananPdfExport
{
    public static function export()
    {
        // Ambil data master indikator & data utama
        $master = MasterAsetPerikanan::orderBy('kdasetperikanan')->get();
        $data = DataAsetPerikanan::with('keluarga')->get();

        // Hitung rata-rata tiap jenis alat
        $indikator = [
            'keramba' => DataAsetPerikanan::avg(DB::raw('CAST(jumlah_keramba AS DECIMAL(10,2))')),
            'tambak'  => DataAsetPerikanan::avg(DB::raw('CAST(jumlah_tambak AS DECIMAL(10,2))')),
            'jermal'  => DataAsetPerikanan::avg(DB::raw('CAST(jumlah_jermal AS DECIMAL(10,2))')),
            'pancing' => DataAsetPerikanan::avg(DB::raw('CAST(jumlah_pancing AS DECIMAL(10,2))')),
            'pukat'   => DataAsetPerikanan::avg(DB::raw('CAST(jumlah_pukat AS DECIMAL(10,2))')),
            'jala'    => DataAsetPerikanan::avg(DB::raw('CAST(jumlah_jala AS DECIMAL(10,2))')),
        ];

        // Total aset perikanan
        $total_aset = array_sum($indikator);

        // Hitung rasio kepemilikan (persentase tiap alat)
        $rasio_keramba = $total_aset > 0 ? $indikator['keramba'] / $total_aset * 100 : 0;
        $rasio_tambak  = $total_aset > 0 ? $indikator['tambak'] / $total_aset * 100 : 0;
        $rasio_pukat   = $total_aset > 0 ? $indikator['pukat'] / $total_aset * 100 : 0;

        // Skor produktivitas aset perikanan (berbobot)
        $skor = (
            ($indikator['keramba'] * 2) +
            ($indikator['tambak'] * 2) +
            ($indikator['pukat'] * 1.5) +
            ($indikator['jermal'] * 1) +
            ($indikator['pancing'] * 1) +
            ($indikator['jala'] * 0.5)
        );

        // Normalisasi skor ke 0–100
        $skor = min(100, round($skor, 2));

        // Kategori dan rekomendasi kebijakan otomatis
        if ($skor < 20) {
            $kategori = 'Sangat Rendah';
            $rekomendasi = [
                'Perlu bantuan alat tangkap dari pemerintah desa.',
                'Program pelatihan budidaya ikan dan pemeliharaan alat.',
                'Dorong kolaborasi antar nelayan untuk efisiensi aset.',
            ];
        } elseif ($skor < 50) {
            $kategori = 'Rendah / Terbatas';
            $rekomendasi = [
                'Tambahkan fasilitas perikanan melalui dana desa.',
                'Diversifikasi alat tangkap dan perawatan berkala.',
                'Optimalkan tambak serta jermal untuk peningkatan hasil.',
            ];
        } elseif ($skor < 80) {
            $kategori = 'Cukup Produktif';
            $rekomendasi = [
                'Tingkatkan kapasitas produksi lewat pelatihan teknis.',
                'Kembangkan pasar hasil tangkapan berbasis digital.',
                'Dukung sistem logistik dan pengolahan ikan di desa.',
            ];
        } else {
            $kategori = 'Sangat Produktif / Mandiri';
            $rekomendasi = [
                'Pertahankan dan ekspansi produksi ikan unggulan.',
                'Kembangkan inovasi budidaya berkelanjutan.',
                'Jadikan contoh percontohan perikanan desa mandiri.',
            ];
        }

        // Buat PDF dari view
        $pdf = Pdf::loadView('exports.asetperikanan_pdf', [
            'data' => $data,
            'master' => $master,
            'indikator' => $indikator,
            'skor' => $skor,
            'kategori' => $kategori,
            'rekomendasi' => $rekomendasi,
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        // Nama file otomatis
        $filename = 'Laporan_Aset_Perikanan_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
