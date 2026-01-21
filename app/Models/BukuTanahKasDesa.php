<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuTanahKasDesa extends Model
{
    protected $table = 'buku_tanah_kas_desa';
    protected $primaryKey = 'kdtanahkasdesa';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kdtanahkasdesa',
        'asaltanahkasdesa',
        'sertifikattanahkasdesa',
        'luastanahkasdesa',
        'kelastanahkasdesa',
        'tanggaltanahkasdesa',
        'kdperolehantkd',
        'kdjenistkd',
        'kdpatok',
        'kdpapannama',
        'lokasitanahkasdesa',
        'peruntukantanahkasdesa',
        'mutasitanahkasdesa',
        'keterangantanahkasdesa',
        'fototanahkasdesa',
        'userinput',
        'inputtime'
    ];

    public function perolehan()
    {
        return $this->belongsTo(MasterPerolehanTKD::class, 'kdperolehantkd');
    }

    public function jenis()
    {
        return $this->belongsTo(MasterJenisTKD::class, 'kdjenistkd');
    }

    public function patok()
    {
        return $this->belongsTo(MasterPatok::class, 'kdpatok');
    }

    public function papanNama()
    {
        return $this->belongsTo(MasterPapanNama::class, 'kdpapannama');
    }
}
