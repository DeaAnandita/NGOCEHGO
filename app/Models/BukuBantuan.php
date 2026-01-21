<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuBantuan extends Model
{
    protected $table = 'buku_bantuan';
    protected $primaryKey = 'reg';
    public $timestamps = false;

    protected $fillable = [
        'kdsasaran',
        'kdbantuan',
        'bantuan_nama',
        'bantuan_awal',
        'bantuan_akhir',
        'bantuan_jumlah',
        'bantuan_keterangan',
        'kdsumber',
        'userinput',
        'inputtime'
    ];

    public function sasaran()
    {
        return $this->belongsTo(MasterSasaran::class, 'kdsasaran');
    }
    public function bantuan()
    {
        return $this->belongsTo(MasterBantuan::class, 'kdbantuan');
    }
    public function sumber()
    {
        return $this->belongsTo(MasterSumberDana::class, 'kdsumber');
    }
}
