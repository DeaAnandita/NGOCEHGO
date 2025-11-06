<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataSosialEkonomi;
use App\Models\MasterPartisipasiSekolah;
use App\Models\MasterIjasahTerakhir;
use App\Models\MasterJenisDisabilitas;
use App\Models\MasterTingkatSulitDisabilitas;
use App\Models\MasterPenyakitKronis;
use App\Models\MasterLapanganUsaha;
use App\Models\MasterStatusKedudukanKerja;
use App\Models\MasterPendapatanPerBulan;
use App\Models\MasterImunisasi;
use Illuminate\Support\Facades\Response;

class DataSosialEkonomiExport
{
    public static function export()
    {
        try {
            // === Nama file dan path penyimpanan ===
            $filename = 'data_sosial_ekonomi_' . date('Ymd_His') . '.xlsx';
            $path = storage_path("app/public/{$filename}");

            // === Buat writer Excel ===
            $writer = SimpleExcelWriter::create($path);

            // === Ambil daftar master data ===
            $partisipasiList = MasterPartisipasiSekolah::orderBy('kdpartisipasisekolah')
                ->pluck('partisipasisekolah', 'kdpartisipasisekolah')
                ->toArray();

            $ijasahList = MasterIjasahTerakhir::orderBy('kdijasahterakhir')
                ->pluck('ijasahterakhir', 'kdijasahterakhir')
                ->toArray();

            $disabilitasList = MasterJenisDisabilitas::orderBy('kdjenisdisabilitas')
                ->pluck('jenisdisabilitas', 'kdjenisdisabilitas')
                ->toArray();

            $tingkatSulitList = MasterTingkatSulitDisabilitas::orderBy('kdtingkatsulitdisabilitas')
                ->pluck('tingkatsulitdisabilitas', 'kdtingkatsulitdisabilitas')
                ->toArray();

            $penyakitList = MasterPenyakitKronis::orderBy('kdpenyakitkronis')
                ->pluck('penyakitkronis', 'kdpenyakitkronis')
                ->toArray();

            $lapanganUsahaList = MasterLapanganUsaha::orderBy('kdlapanganusaha')
                ->pluck('lapanganusaha', 'kdlapanganusaha')
                ->toArray();

            $statusKerjaList = MasterStatusKedudukanKerja::orderBy('kdstatuskedudukankerja')
                ->pluck('statuskedudukankerja', 'kdstatuskedudukankerja')
                ->toArray();

            $pendapatanList = MasterPendapatanPerBulan::orderBy('kdpendapatanperbulan')
                ->pluck('pendapatanperbulan', 'kdpendapatanperbulan')
                ->toArray();

            $imunisasiList = MasterImunisasi::orderBy('kdimunisasi')
                ->pluck('imunisasi', 'kdimunisasi')
                ->toArray();

            // === Header kolom Excel ===
            $header = [
                'NIK',
                'Nama Lengkap',
                'Partisipasi Sekolah',
                'Ijazah Terakhir',
                'Jenis Disabilitas',
                'Tingkat Kesulitan Disabilitas',
                'Penyakit Kronis',
                'Lapangan Usaha',
                'Status Kedudukan Kerja',
                'Pendapatan Per Bulan',
                'Imunisasi',
            ];

            $writer->addHeader($header);

            // === Ambil data sosial ekonomi + relasi penduduk ===
            $dataSosial = DataSosialEkonomi::with('penduduk')->get();

            // === Tambahkan baris data ===
            foreach ($dataSosial as $data) {
                $row = [
                    'NIK' => $data->nik ?? '-',
                    'Nama Lengkap' => $data->penduduk->penduduk_namalengkap ?? '-',
                    'Partisipasi Sekolah' => $partisipasiList[$data->kdpartisipasisekolah ?? 0] ?? 'TIDAK DIISI',
                    'Ijazah Terakhir' => $ijasahList[$data->kdijasahterakhir ?? 0] ?? 'TIDAK DIISI',
                    'Jenis Disabilitas' => $disabilitasList[$data->kdjenisdisabilitas ?? 0] ?? 'TIDAK DIISI',
                    'Tingkat Kesulitan Disabilitas' => $tingkatSulitList[$data->kdtingkatsulitdisabilitas ?? 0] ?? 'TIDAK DIISI',
                    'Penyakit Kronis' => $penyakitList[$data->kdpenyakitkronis ?? 0] ?? 'TIDAK DIISI',
                    'Lapangan Usaha' => $lapanganUsahaList[$data->kdlapanganusaha ?? 0] ?? 'TIDAK DIISI',
                    'Status Kedudukan Kerja' => $statusKerjaList[$data->kdstatuskedudukankerja ?? 0] ?? 'TIDAK DIISI',
                    'Pendapatan Per Bulan' => $pendapatanList[$data->kdpendapatanperbulan ?? 0] ?? 'TIDAK DIISI',
                    'Imunisasi' => $imunisasiList[$data->kdimunisasi ?? 0] ?? 'TIDAK DIISI',
                ];

                $writer->addRow($row);
            }

            $writer->close();

            // === Kembalikan response download ===
            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            return Response::make('Terjadi kesalahan saat mengekspor data: ' . $th->getMessage(), 500);
        }
    }
}
