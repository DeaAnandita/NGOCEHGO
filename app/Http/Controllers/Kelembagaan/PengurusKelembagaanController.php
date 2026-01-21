<?php

namespace App\Http\Controllers\Kelembagaan;

use App\Exports\PengurusExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengurusKelembagaan;
use App\Models\{
    MasterJabatanKelembagaan,
    MasterUnitKelembagaan,
    MasterPeriodeKelembagaan,
    MasterPeriodeKelembagaanAkhir,
    MasterStatusPengurusKelembagaan,
    MasterJenisSkKelembagaan
};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PengurusKelembagaanController extends Controller
{
    /* =========================
        INDEX
    ==========================*/
    public function index(Request $request)
    {
        $query = PengurusKelembagaan::with([
            'jabatan',
            'unit',
            'status',
            'jenisSk',
            'periodeAwal',
            'periodeAkhir'
        ]);

        // 🔹 Filter Periode
        if ($request->periode) {
            $query->where('kdperiode', $request->periode);
        }

        // 🔹 Search (nama & nomor induk)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_induk', 'like', '%' . $request->search . '%');
            });
        }

        // 🔹 Per Page
        $perPage = $request->per_page ?? 10;

        $pengurus = $query->paginate($perPage)->withQueryString();

        $listPeriode = MasterPeriodeKelembagaan::orderBy('tahun_awal', 'desc')->get();

        return view('kelembagaan.pengurus.index', compact('pengurus', 'listPeriode', 'perPage'));
    }


    /* =========================
        CREATE
    ==========================*/
    public function create()
    {
        return view('kelembagaan.pengurus.create', [
            'jabatan' => MasterJabatanKelembagaan::all(),
            'unit'    => MasterUnitKelembagaan::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
            'periodeAkhir' => MasterPeriodeKelembagaanAkhir::all(),
            'status'  => MasterStatusPengurusKelembagaan::all(),
            'jenisSk' => MasterJenisSkKelembagaan::all(),
        ]);
    }

    /* =========================
        STORE
    ==========================*/
    public function store(Request $request)
    {
        $request->validate([
            'nomor_induk'       => 'required|digits:16|unique:pengurus_kelembagaan,nomor_induk',
            'nama_lengkap' => 'required|regex:/^[A-Za-z.\s]+$/',
            'jenis_kelamin'    => 'required|in:L,P',
            'no_hp'            => 'required|digits_between:10,15',
            'email'            => 'required|email',

            'kdjabatan'        => 'required',
            'kdunit'           => 'required',
            'kdperiode'        => 'required',
            'kdperiode_akhir'  => 'required',
            'kdstatus'         => 'required',
            'kdjenissk'        => 'required',

            'no_sk'            => 'required',
            'tanggal_sk'       => 'required|date',

            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tanda_tangan'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'keterangan'      => 'nullable|string|max:500',
        ], [
            'nomor_induk.required'  => 'NIK wajib diisi',
            'nomor_induk.digits'    => 'NIK harus 16 digit angka',
            'nomor_induk.unique'    => 'NIK ini sudah terdaftar',

            'nama_lengkap.required' => 'Nama wajib diisi',
            'nama_lengkap.regex'   => 'Nama tidak boleh mengandung angka atau simbol',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',

            'no_hp.required'       => 'Nomor HP wajib diisi',
            'no_hp.digits_between' => 'Nomor HP minimal 10 dan maksimal 15 digit',

            'email.required'       => 'Email wajib diisi',
            'email.email'          => 'Format email tidak valid',

            'kdjabatan.required'   => 'Jabatan wajib dipilih',
            'kdunit.required'      => 'Unit kerja wajib dipilih',
            'kdperiode.required'  => 'Periode awal wajib dipilih',
            'kdperiode_akhir.required' => 'Periode akhir wajib dipilih',
            'kdstatus.required'   => 'Status pengurus wajib dipilih',
            'kdjenissk.required'  => 'Jenis SK wajib dipilih',

            'no_sk.required'       => 'Nomor SK wajib diisi',
            'tanggal_sk.required' => 'Tanggal SK wajib diisi',
            'tanggal_sk.date'     => 'Tanggal SK tidak valid',

            'foto.image'          => 'Foto harus berupa gambar',
            'tanda_tangan.image'  => 'Tanda tangan harus berupa gambar',
        ]);

        $data = $request->only([
            'nomor_induk',
            'nama_lengkap',
            'jenis_kelamin',
            'no_hp',
            'email',
            'alamat',
            'kdjabatan',
            'kdunit',
            'kdperiode',
            'kdperiode_akhir',
            'kdstatus',
            'kdjenissk',
            'no_sk',
            'tanggal_sk',
            'keterangan',
        ]);

        // Upload Foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pengurus/foto', 'public');
        }

        // Upload Tanda Tangan
        if ($request->hasFile('tanda_tangan')) {
            $data['tanda_tangan'] = $request->file('tanda_tangan')->store('pengurus/ttd', 'public');
        }

        PengurusKelembagaan::create($data);

        return redirect()
            ->route('kelembagaan.pengurus.index')
            ->with('success', 'Pengurus berhasil ditambahkan');
    }


    /* =========================
        SHOW
    ==========================*/
    public function show($id)
    {
        $pengurus = PengurusKelembagaan::with([
            'jabatan',
            'unit',
            'periodeAwal',
            'periodeAkhir',
            'status',
            'jenisSk'
        ])->findOrFail($id);

        return view('kelembagaan.pengurus.show', compact('pengurus'));
    }

    /* =========================
        EDIT
    ==========================*/
    public function edit($id)
    {
        $pengurus = PengurusKelembagaan::findOrFail($id);

        return view('kelembagaan.pengurus.edit', [
            'pengurus' => $pengurus,
            'jabatan' => MasterJabatanKelembagaan::all(),
            'unit' => MasterUnitKelembagaan::all(),
            'periode' => MasterPeriodeKelembagaan::all(),
            'periodeAkhir' => MasterPeriodeKelembagaanAkhir::all(),
            'status' => MasterStatusPengurusKelembagaan::all(),
            'jenisSk' => MasterJenisSkKelembagaan::all(),
        ]);
    }

    /* =========================
        UPDATE
    ==========================*/
    public function update(Request $request, $id)
    {
        $pengurus = PengurusKelembagaan::findOrFail($id);

        $request->validate([
            'nomor_induk' => 'required|digits:16|unique:pengurus_kelembagaan,nomor_induk,' . $id . ',id_pengurus',
            'nama_lengkap' => 'required|regex:/^[A-Za-z.\s]+$/',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|digits_between:10,15',
            'email' => 'required|email',
            'alamat' => 'nullable|string',

            'kdjabatan' => 'required',
            'kdunit' => 'required',
            'kdperiode' => 'required',
            'kdperiode_akhir' => 'required',
            'kdstatus' => 'required',
            'kdjenissk' => 'required',

            'no_sk' => 'required',
            'tanggal_sk' => 'required|date',

            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tanda_tangan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'keterangan' => 'nullable|string|max:500',
        ], [
            'nomor_induk.required' => 'Nomor Induk wajib diisi',
            'nomor_induk.digits' => 'Nomor Induk harus 16 digit',
            'nomor_induk.unique' => 'Nomor Induk sudah terdaftar',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nama_lengkap.regex' => 'Nama tidak boleh mengandung angka',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',

            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.digits_between' => 'Nomor HP minimal 10 dan maksimal 15 digit',

            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',

            'kdjabatan.required' => 'Jabatan wajib dipilih',
            'kdunit.required' => 'Unit kerja wajib dipilih',
            'kdperiode.required' => 'Periode awal wajib dipilih',
            'kdperiode_akhir.required' => 'Periode akhir wajib dipilih',
            'kdstatus.required' => 'Status pengurus wajib dipilih',
            'kdjenissk.required' => 'Jenis SK wajib dipilih',

            'no_sk.required' => 'Nomor SK wajib diisi',
            'tanggal_sk.required' => 'Tanggal SK wajib diisi',

            'foto.image' => 'File foto harus berupa gambar',
            'tanda_tangan.image' => 'File tanda tangan harus berupa gambar',
        ]);

        $data = $request->only([
            'nomor_induk',
            'nama_lengkap',
            'jenis_kelamin',
            'no_hp',
            'email',
            'alamat',
            'kdjabatan',
            'kdunit',
            'kdperiode',
            'kdperiode_akhir',
            'kdstatus',
            'kdjenissk',
            'no_sk',
            'tanggal_sk',
            'keterangan',
        ]);

        // Upload foto baru
        if ($request->hasFile('foto')) {
            if ($pengurus->foto && Storage::disk('public')->exists($pengurus->foto)) {
                Storage::disk('public')->delete($pengurus->foto);
            }
            $data['foto'] = $request->file('foto')->store('pengurus/foto', 'public');
        }

        // Upload tanda tangan baru
        if ($request->hasFile('tanda_tangan')) {
            if ($pengurus->tanda_tangan && Storage::disk('public')->exists($pengurus->tanda_tangan)) {
                Storage::disk('public')->delete($pengurus->tanda_tangan);
            }
            $data['tanda_tangan'] = $request->file('tanda_tangan')->store('pengurus/ttd', 'public');
        }

        $pengurus->update($data);

        return redirect()
            ->route('kelembagaan.pengurus.index')
            ->with('success', 'Data pengurus berhasil diperbarui');
    }



    /* =========================
        DELETE
    ==========================*/
    public function destroy($id)
    {
        PengurusKelembagaan::findOrFail($id)->delete();

        return redirect()
            ->route('kelembagaan.pengurus.index')
            ->with('success', 'Data pengurus berhasil dihapus');
    }
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PengurusExport($request->periode),
            'data_pengurus.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $query = PengurusKelembagaan::with([
            'jabatan',
            'unit',
            'status',
            'jenisSk',
            'periodeAwal',
            'periodeAkhir'
        ]);

        if ($request->periode) {
            $query->where('kdperiode', $request->periode);
        }

        $pengurus = $query->get();

        $periode = $request->periode
            ? MasterPeriodeKelembagaan::where('kdperiode', $request->periode)->first()
            : null;

        $pdf = Pdf::loadView('kelembagaan.pengurus.export_pdf', [
            'pengurus' => $pengurus,
            'periode' => $periode
        ])->setPaper('A4', 'landscape');

        return $pdf->download('data_pengurus.pdf');
    }
}
