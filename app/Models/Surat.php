<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surats';

    protected $fillable = [
        'user_id',
        'nik',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'kewarganegaraan',
        'agama',
        'pekerjaan',
        'alamat',

        // Data surat
        'nomor_surat',
        'tanggal_surat',
        'keperluan',
        'keterangan_lain',
        'status',

        // Cetak
        'cetak_token',
        'barcode_cetak_path',

        // Verifikasi
        'kode_verifikasi',
        'barcode_verifikasi_path',

        // Approval
        'approved_by',
        'approved_at',
        'printed_at',

        // Pemilik pengajuan
        'user_id'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_surat' => 'date',
        'approved_at'   => 'datetime',
        'printed_at'    => 'datetime',
    ];

    // ===========================
    // RELATION
    // ===========================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ===========================
    // SCOPES
    // ===========================

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDicetak($query)
    {
        return $query->where('status', 'dicetak');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    // ===========================
    // HELPER STATUS
    // ===========================

    public function isMenunggu()
    {
        return $this->status === 'menunggu';
    }

    public function isDisetujui()
    {
        return $this->status === 'disetujui';
    }

    public function isDicetak()
    {
        return $this->status === 'dicetak';
    }

    public function isDitolak()
    {
        return $this->status === 'ditolak';
    }

}
