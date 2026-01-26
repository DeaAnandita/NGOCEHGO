<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataPenduduk extends Model
{
    use HasFactory;

    protected $table = 'data_penduduk';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nik',
        'no_kk',
        'kdmutasimasuk',
        'penduduk_tanggalmutasi',
        'penduduk_kewarganegaraan',
        'penduduk_nourutkk',
        'penduduk_goldarah',
        'penduduk_noaktalahir',
        'penduduk_namalengkap',
        'penduduk_tempatlahir',
        'penduduk_tanggallahir',
        'kdjeniskelamin',
        'kdagama',
        'kdhubungankeluarga',
        'kdhubungankepalakeluarga',
        'kdstatuskawin',
        'kdaktanikah',
        'kdtercantumdalamkk',
        'kdstatustinggal',
        'kdkartuidentitas',
        'penduduk_namaayah',
        'penduduk_namaibu',
        'kdpekerjaan',
        'penduduk_namatempatbekerja',
        'kdprovinsi',
        'kdkabupaten',
        'kdkecamatan',
        'kddesa',
    ];


    /**
     * Relasi ke data kelahiran.
     */
    public function kelahiran()
    {
        return $this->hasMany(Kelahiran::class, 'nik', 'nik');
    }

    /**
     * Relasi ke data sosialekonomi.
     */
    public function sosialekonomi()
    {
        return $this->hasMany(DataSosialEkonomi::class, 'nik', 'nik');
    }

    /**
     * Relasi ke data usahaart.
     */
    public function usahaart()
    {
        return $this->hasMany(DataUsahaArt::class, 'nik', 'nik');
    }

    /**
     * Relasi ke data programserta.
     */
    public function programserta()
    {
        return $this->hasMany(DataProgramSerta::class, 'nik', 'nik');
    }

    /**
     * Relasi ke data lemdes.
     */
    public function lemdes()
    {
        return $this->hasMany(DataLemdes::class, 'nik', 'nik');
    }

    /**
     * Relasi ke data lemmas.
     */
    public function lemmas()
    {
        return $this->hasMany(DataLemmas::class, 'nik', 'nik');
    }

    /**
     * Relasi ke data lemek.
     */
    public function lemek()
    {
        return $this->hasMany(DataLemek::class, 'nik', 'nik');
    }

    /**
     * Relasi ke data keluarga.
     */
    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'no_kk', 'no_kk');
    }

    /**
     * Relasi ke semua master terkait.
     */
    public function jeniskelamin()
    {
        return $this->belongsTo(MasterJenisKelamin::class, 'kdjeniskelamin', 'kdjeniskelamin');
    }

    public function agama()
    {
        return $this->belongsTo(MasterAgama::class, 'kdagama', 'kdagama');
    }

    public function hubungankeluarga()
    {
        return $this->belongsTo(MasterHubunganKeluarga::class, 'kdhubungankeluarga', 'kdhubungankeluarga');
    }

    public function hubungankepalakeluarga()
    {
        return $this->belongsTo(MasterHubunganKepalaKeluarga::class, 'kdhubungankepalakeluarga', 'kdhubungankepalakeluarga');
    }

    public function statuskawin()
    {
        return $this->belongsTo(MasterStatusKawin::class, 'kdstatuskawin', 'kdstatuskawin');
    }

    public function aktanikah()
    {
        return $this->belongsTo(MasterAktaNikah::class, 'kdaktanikah', 'kdaktanikah');
    }

    public function tercantumdalamKk()
    {
        return $this->belongsTo(MasterTercantumDalamKk::class, 'kdtercantumdalamkk', 'kdtercantumdalamkk');
    }

    public function statustinggal()
    {
        return $this->belongsTo(MasterStatusTinggal::class, 'kdstatustinggal', 'kdstatustinggal');
    }

    public function kartuidentitas()
    {
        return $this->belongsTo(MasterKartuIdentitas::class, 'kdkartuidentitas', 'kdkartuidentitas');
    }

    public function pekerjaan()
    {
        return $this->belongsTo(MasterPekerjaan::class, 'kdpekerjaan', 'kdpekerjaan');
    }
    public function cekNik(Request $request)
    {
        $request->validate([
            'nik' => ['required', 'digits:16']
        ]);

        $p = DataPenduduk::find($request->nik); // karena PK = nik

        if (!$p) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'data' => [
                'nik' => $p->nik,
                'nama' => $p->penduduk_namalengkap,
                'tempat_lahir' => $p->penduduk_tempatlahir,
                'tanggal_lahir' => $p->penduduk_tanggallahir,
                'kewarganegaraan' => $p->penduduk_kewarganegaraan,
                // catatan: jenis_kelamin/agama/pekerjaan/alamat belum bisa otomatis tanpa join / kolom teks
            ]
        ]);
    }
}
