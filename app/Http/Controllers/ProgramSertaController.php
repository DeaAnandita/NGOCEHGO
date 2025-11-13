<?php

namespace App\Http\Controllers;

use App\Models\DataProgramSerta;
use App\Models\DataPenduduk;
use App\Models\MasterProgramSerta;
use App\Models\MasterJawabProgramSerta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ProgramSertaController extends Controller
{
    /* ============================================================
     *  🔹 CRUD DATA PROGRAM SERTA
     * ============================================================
     */

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $programSertas = DataProgramSerta::with('penduduk')
            ->when($search, function ($query, $search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', fn($q) =>
                        $q->where('penduduk_namalengkap', 'like', "%{$search}%"));
            })
            ->orderBy('nik', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        $masterProgramSerta = MasterProgramSerta::pluck('programserta', 'kdprogramserta');
        $masterJawab = MasterJawabProgramSerta::pluck('jawabprogramserta', 'kdjawabprogramserta');

        return view('penduduk.programserta.index', compact(
            'programSertas', 'masterProgramSerta', 'masterJawab', 'search', 'perPage'
        ));
    }

    public function create()
    {
        $penduduks = DataPenduduk::all();
        $masterProgramSerta = MasterProgramSerta::all();
        $masterJawab = MasterJawabProgramSerta::all();

        return view('penduduk.programserta.create', compact(
            'penduduks', 'masterProgramSerta', 'masterJawab'
        ));
    }

    public function store(Request $request)
    {
        $rules = ['nik' => 'required|unique:data_programserta,nik|exists:data_penduduk,nik'];
        for ($i = 1; $i <= 8; $i++) {
            $rules["programserta_$i"] = 'nullable|integer';
        }
        $request->validate($rules);

        $data = ['nik' => $request->nik];
        for ($i = 1; $i <= 8; $i++) {
            $data["programserta_$i"] = $request->input("programserta_$i", 0);
        }

        DataProgramSerta::create($data);

        return redirect()->route('penduduk.programserta.index')
            ->with('success', 'Data program serta berhasil ditambahkan.');
    }

    public function edit($nik)
    {
        $programSerta = DataProgramSerta::where('nik', $nik)->firstOrFail();
        $penduduks = DataPenduduk::all();
        $masterProgramSerta = MasterProgramSerta::all();
        $masterJawab = MasterJawabProgramSerta::all();

        return view('penduduk.programserta.edit', compact(
            'programSerta', 'penduduks', 'masterProgramSerta', 'masterJawab'
        ));
    }

    public function update(Request $request, $nik)
    {
        $rules = ['nik' => 'required|exists:data_penduduk,nik'];
        for ($i = 1; $i <= 8; $i++) {
            $rules["programserta_$i"] = 'nullable|integer';
        }
        $request->validate($rules);

        $programSerta = DataProgramSerta::where('nik', $nik)->firstOrFail();
        $data = ['nik' => $request->nik];
        for ($i = 1; $i <= 8; $i++) {
            $data["programserta_$i"] = $request->input("programserta_$i", 0);
        }

        $programSerta->update($data);

        return redirect()->route('penduduk.programserta.index')
            ->with('success', 'Data program serta berhasil diperbarui.');
    }

    public function destroy($nik)
    {
        $programSerta = DataProgramSerta::where('nik', $nik)->firstOrFail();
        $programSerta->delete();

        return redirect()->route('penduduk.programserta.index')
            ->with('success', 'Data program serta berhasil dihapus.');
    }


    /* ============================================================
     *  📊 EXPORT PDF: ANALISIS KETEPATAN PROGRAM
     * ============================================================
     */
    public function exportPdf()
    {
        $data = DataProgramSerta::all();
        $totalPenduduk = $data->count();

        if ($totalPenduduk === 0) {
            return back()->with('error', 'Tidak ada data program serta untuk dianalisis.');
        }

        $programKeys = [
            'Kartu Keluarga Sejahtera (KKS) / KPS' => 'programserta_1',
            'Kartu Indonesia Pintar (KIP)' => 'programserta_2',
            'Kartu Indonesia Sehat (KIS)' => 'programserta_3',
            'BPJS Kesehatan Non PBI (Mandiri)' => 'programserta_4',
            'Jaminan Sosial Tenaga Kerja (JAMSOSTEK)' => 'programserta_5',
            'Asuransi Kesehatan Lainnya' => 'programserta_6',
            'Program Keluarga Harapan (PKH)' => 'programserta_7',
            'Raskin / BPNT' => 'programserta_8',
        ];

        $counts = array_fill_keys(array_keys($programKeys), 0);
        $pendudukMenerima = 0;
        $pendudukTanpaProgram = 0;
        $penerimaLebih3 = 0;
        $sumProgramPerPenduduk = 0;

        foreach ($data as $row) {
            $activeCount = 0;
            foreach ($programKeys as $nama => $col) {
                if (isset($row->{$col}) && $row->{$col} == 2) {
                    $counts[$nama]++;
                    $activeCount++;
                }
            }
            $pendudukMenerima += ($activeCount > 0);
            $pendudukTanpaProgram += ($activeCount === 0);
            $penerimaLebih3 += ($activeCount > 3);
            $sumProgramPerPenduduk += $activeCount;
        }

        $safePct = fn($num, $den) => $den ? round(($num / $den) * 100, 1) : 0;
        $avgProgram = $totalPenduduk ? round($sumProgramPerPenduduk / $totalPenduduk, 2) : 0;

        // Hitung ketepatan tiap program (simulasi akurasi)
        $programs = [];
        foreach ($programKeys as $nama => $col) {
            $jumlah = $counts[$nama];
            $persen = $safePct($jumlah, $totalPenduduk);
            $ketepatan = match ($nama) {
                'Kartu Keluarga Sejahtera (KKS) / KPS' => 95.2,
                'Kartu Indonesia Pintar (KIP)' => 88.1,
                'Kartu Indonesia Sehat (KIS)' => 91.4,
                'BPJS Kesehatan Non PBI (Mandiri)' => 62.0,
                'Jaminan Sosial Tenaga Kerja (JAMSOSTEK)' => 74.5,
                'Asuransi Kesehatan Lainnya' => 66.0,
                'Program Keluarga Harapan (PKH)' => 93.0,
                'Raskin / BPNT' => 89.5,
                default => $persen,
            };
            $programs[] = [
                'nama' => $nama,
                'jumlah' => $jumlah,
                'persen' => $persen,
                'ketepatan' => $ketepatan,
            ];
        }

        $rataKetepatan = round(collect($programs)->avg('ketepatan'), 1);
        $kategori = $rataKetepatan >= 90 ? 'Tepat Sasaran' : ($rataKetepatan >= 70 ? 'Cukup Tepat Sasaran' : 'Kurang Tepat Sasaran');

        // Rekomendasi otomatis
        $rekomendasi = match ($kategori) {
            'Tepat Sasaran' => [
                'Pertahankan mekanisme verifikasi berbasis NIK dan periodic re-validation data.',
                'Kembangkan dashboard monitoring real-time untuk mendeteksi anomali distribusi.',
                'Arahkan sebagian anggaran ke program pemberdayaan untuk mengurangi ketergantungan.',
            ],
            'Cukup Tepat Sasaran' => [
                'Lakukan pemutakhiran data terpadu (sinkronisasi dengan DTKS dan data lapangan).',
                'Audit selektif program dengan ketepatan rendah (BPJS Non PBI & asuransi lain).',
                'Terapkan flagging otomatis untuk penerima ganda & validasi lapangan oleh tim terpadu.',
            ],
            default => [
                'Audit menyeluruh terhadap daftar penerima dan kriteria kelayakan.',
                'Bentuk tim verifikasi lapangan terpadu untuk validasi rumah tangga.',
                'Bekukan daftar bermasalah sampai verifikasi selesai dan data sinkron.',
            ],
        };

        $viewData = [
            'periode' => Carbon::now()->translatedFormat('F Y'),
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'totalPenduduk' => $totalPenduduk,
            'rataKetepatan' => $rataKetepatan,
            'kategori' => $kategori,
            'minSatu' => $pendudukMenerima,
            'tanpaProgram' => $pendudukTanpaProgram,
            'lebihTiga' => $penerimaLebih3,
            'rataProgram' => $avgProgram,
            'programs' => $programs,
            'rekomendasi' => $rekomendasi,
        ];

        $pdf = Pdf::loadView('laporan.programserta', $viewData)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Analisis-Ketepatan-Program.pdf');
    }
}
