<?php

namespace App\Http\Controllers\Pelayanan;


use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\DataPenduduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SuratController extends Controller
{
    public function __construct()
    {
        \Carbon\Carbon::setLocale('id');
    }

    // ==============================
    // LIST DATA
    // ==============================
    public function index()
    {
        $user = Auth::user();

        $surats = Surat::query()
            ->when($user->role_id == 2, function ($q) use ($user) {
                // user biasa: hanya lihat surat miliknya
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelayanan.surat.index', compact('surats'));
    }


    // ==============================
    // FORM BUAT
    // ==============================
    public function create()
    {
        return view('pelayanan.surat.create');
    }

    // ==============================
    // API CEK NIK (AUTOFILL)
    // ==============================
    public function cekNik(Request $request)
    {
        $nik = preg_replace('/\D/', '', (string) $request->query('nik'));

        if (!preg_match('/^\d{16}$/', $nik)) {
            return response()->json(['found' => false, 'message' => 'NIK tidak valid'], 422);
        }

        $p = DataPenduduk::where('nik', $nik)->first(); // data kamu sudah bersih, ga perlu whereRaw

        if (!$p) {
            return response()->json(['found' => false]);
        }

        $jk = $this->resolveJenisKelamin($p->kdjeniskelamin ?? null);

        $agama = $this->resolveMasterName('master_agama', 'kdagama', $p->kdagama ?? null, [
            'agama',
            'nama_agama',
            'master_agama_nama',
            'keterangan',
            'nama',
        ]);

        return response()->json([
            'found' => true,
            'data' => [
                'nik' => $p->nik,
                'nama' => $p->penduduk_namalengkap,
                'tempat_lahir' => $p->penduduk_tempatlahir,
                'tanggal_lahir' => $p->penduduk_tanggallahir,
                'jenis_kelamin' => $jk, // ✅ penting untuk autofill
                'kewarganegaraan' => $p->penduduk_kewarganegaraan ?? 'INDONESIA',
                'agama' => $agama, // ✅ penting untuk autofill
            ],
        ]);
    }

    // ==============================
    // SIMPAN
    // ==============================
    public function store(Request $request)
    {
        // kalau request dari fetch (Accept: application/json) dan validasi gagal,
        // Laravel akan balikin 422 JSON otomatis.
        $data = $request->validate([
            'nik'              => 'required|digits:16',
            'nama'             => 'required|string|max:100',
            'tempat_lahir'     => 'required|string|max:100',
            'tanggal_lahir'    => 'required|date',
            'jenis_kelamin'    => 'required|in:L,P',      // ✅ jadi required biar jelas
            'kewarganegaraan'  => 'required|string|max:50',
            'agama'            => 'required|string|max:50',

            'pekerjaan'        => 'required|string|max:100',
            'alamat'           => 'required|string',
            'keperluan'        => 'required|string',
            'keterangan_lain'  => 'nullable|string',
        ]);

        // Kalau NIK ada di data_penduduk, override identitas + JK + agama (lebih konsisten)
        $p = DataPenduduk::where('nik', $data['nik'])->first();
        if ($p) {
            $data['nama'] = $p->penduduk_namalengkap;
            $data['tempat_lahir'] = $p->penduduk_tempatlahir;
            $data['tanggal_lahir'] = $p->penduduk_tanggallahir;
            $data['kewarganegaraan'] = $p->penduduk_kewarganegaraan ?? $data['kewarganegaraan'];

            $jk = $this->resolveJenisKelamin($p->kdjeniskelamin ?? null);
            if ($jk) $data['jenis_kelamin'] = $jk;

            $agama = $this->resolveMasterName('master_agama', 'kdagama', $p->kdagama ?? null, [
                'agama',
                'nama_agama',
                'master_agama_nama',
                'keterangan',
                'nama',
            ]);
            if ($agama) $data['agama'] = $agama;
        }

        $data['status'] = 'menunggu';
        $data['user_id'] = Auth::id();

        Surat::create($data);

        return redirect()
            ->route('pelayanan.surat.index')
            ->with('success', 'Pengajuan surat berhasil dibuat.');
    }


    // ==============================
    // DETAIL
    // ==============================
    public function show($id)
    {
        $surat = Surat::findOrFail($id);
        $user = Auth::user();

        if ($user->role_id == 2 && $surat->user_id != $user->id) {
            abort(403);
        }

        return view('pelayanan.surat.show', compact('surat'));
    }

    // ==============================
    // APPROVE
    // ==============================
    public function approve($id)
    {
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 3])) abort(403);

        $surat = Surat::findOrFail($id);

        $cetakToken = $surat->cetak_token ?: Str::random(32);
        $kodeVerifikasi = $surat->kode_verifikasi ?: 'VER-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        $qrFolder = public_path('qrcodes');
        if (!file_exists($qrFolder)) mkdir($qrFolder, 0777, true);

        $qrCetakName = 'qr-cetak-' . $surat->id . '.svg';
        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(300)
            ->generate(url('/surat/cetak/' . $cetakToken), $qrFolder . '/' . $qrCetakName);

        $qrVerifName = 'qr-verifikasi-' . $surat->id . '.svg';
        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(300)
            ->generate(route('surat.verifikasi', $kodeVerifikasi), $qrFolder . '/' . $qrVerifName);

        $nomorSurat = $surat->nomor_surat ?: $this->generateNomorSurat();

        $surat->update([
            'status' => 'disetujui',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'nomor_surat' => $nomorSurat,
            'tanggal_surat' => now(),
            'cetak_token' => $cetakToken,
            'kode_verifikasi' => $kodeVerifikasi,
            'barcode_cetak_path' => 'qrcodes/' . $qrCetakName,
            'barcode_verifikasi_path' => 'qrcodes/' . $qrVerifName,
        ]);

        return back()->with('success', 'Surat berhasil disetujui.');
    }

    // ==============================
    // TOLAK
    // ==============================
    public function reject($id)
    {
        if (!in_array(Auth::user()->role_id, [1, 3])) abort(403);

        Surat::findOrFail($id)->update(['status' => 'ditolak']);

        return back()->with('success', 'Surat ditolak.');
    }

    // ==============================
    // CETAK
    // ==============================
    public function print($token)
    {
        $surat = Surat::where('cetak_token', $token)->firstOrFail();
        return view('pelayanan.surat.print', compact('surat'));
    }

    public function previewById($id)
    {
        $surat = Surat::findOrFail($id);
        return view('pelayanan.surat.preview', compact('surat'));
    }

    public function preview($token)
    {
        $surat = Surat::where('cetak_token', $token)->firstOrFail();
        return view('pelayanan.surat.preview', compact('surat'));
    }

    // ==============================
    // VERIFIKASI
    // ==============================
    public function verifikasi($kode)
    {
        $surat = Surat::where('kode_verifikasi', $kode)->first();
        return view('pelayanan.surat.verifikasi', [
            'valid' => $surat ? true : false,
            'surat' => $surat
        ]);
    }

    // ==============================
    // NOMOR SURAT
    // ==============================
    private function generateNomorSurat()
    {
        $year = now()->year;
        $count = Surat::whereYear('tanggal_surat', $year)->count() + 1;
        $bulan = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"][now()->month];
        return "{$count}/28.07.14/{$bulan}/228/{$year}";
    }

    public function download($token)
    {
        $surat = Surat::where('cetak_token', $token)
            ->where('status', 'disetujui')
            ->firstOrFail();

        $pdf = Pdf::loadView('pelayanan.surat.pdf', compact('surat'))
            ->setPaper('A4', 'portrait');

        $filename = 'Surat-' . preg_replace('/[^A-Za-z0-9\-]/', '-', $surat->nomor_surat) . '.pdf';

        return $pdf->download($filename);
    }

    // ==============================
    // HELPERS (MASTER LOOKUP)
    // ==============================
    private function resolveMasterName(string $table, string $keyCol, $keyVal, array $candidateNameCols): ?string
    {
        if (!$keyVal) return null;
        if (!Schema::hasTable($table)) return null;
        if (!Schema::hasColumn($table, $keyCol)) return null;

        $nameCol = null;
        foreach ($candidateNameCols as $col) {
            if (Schema::hasColumn($table, $col)) {
                $nameCol = $col;
                break;
            }
        }
        if (!$nameCol) return null;

        $row = DB::table($table)->where($keyCol, $keyVal)->first([$nameCol]);
        if (!$row) return null;

        $val = $row->{$nameCol} ?? null;
        return $val ? (string) $val : null;
    }

    private function resolveJenisKelamin($kd): ?string
    {
        if (!$kd) return null;

        // coba ambil dari master_jeniskelamin jika ada (lebih aman)
        if (Schema::hasTable('master_jeniskelamin') && Schema::hasColumn('master_jeniskelamin', 'kdjeniskelamin')) {
            $nameCol = null;
            foreach (['kode', 'inisial', 'jenis_kelamin', 'jeniskelamin', 'nama_jeniskelamin', 'nama'] as $c) {
                if (Schema::hasColumn('master_jeniskelamin', $c)) {
                    $nameCol = $c;
                    break;
                }
            }
            if ($nameCol) {
                $row = DB::table('master_jeniskelamin')->where('kdjeniskelamin', $kd)->first([$nameCol]);
                $val = $row->{$nameCol} ?? null;
                if ($val) {
                    $v = strtoupper(trim((string)$val));
                    if ($v === 'L' || $v === 'P') return $v;
                    if (str_contains($v, 'PEREM')) return 'P';
                    if (str_contains($v, 'WANITA')) return 'P';
                    if (str_contains($v, 'LAKI')) return 'L';
                    if (str_contains($v, 'PRIA')) return 'L';
                }
            }
        }

        // fallback umum: 1=L, 2=P
        if ((int)$kd === 2) return 'P';
        return 'L';
    }

    private function buildAlamatWilayah($kddesa, $kdkecamatan, $kdkabupaten, $kdprovinsi): ?string
    {
        $desa = $this->resolveMasterName('master_desa', 'kddesa', $kddesa, ['nama_desa', 'desa', 'nm_desa', 'nama', 'keterangan']);
        $kec  = $this->resolveMasterName('master_kecamatan', 'kdkecamatan', $kdkecamatan, ['nama_kecamatan', 'kecamatan', 'nm_kecamatan', 'nama', 'keterangan']);
        $kab  = $this->resolveMasterName('master_kabupaten', 'kdkabupaten', $kdkabupaten, ['nama_kabupaten', 'kabupaten', 'nm_kabupaten', 'nama', 'keterangan']);
        $prov = $this->resolveMasterName('master_provinsi', 'kdprovinsi', $kdprovinsi, ['nama_provinsi', 'provinsi', 'nm_provinsi', 'nama', 'keterangan']);

        $parts = [];
        if ($desa) $parts[] = "Desa {$desa}";
        if ($kec)  $parts[] = "Kecamatan {$kec}";
        if ($kab)  $parts[] = "Kabupaten {$kab}";
        if ($prov) $parts[] = "Provinsi {$prov}";

        return count($parts) ? implode(', ', $parts) : null;
    }
}
