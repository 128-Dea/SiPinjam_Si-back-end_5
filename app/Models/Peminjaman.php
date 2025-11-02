<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'pengguna_id',
        'barang_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'catatan'
    ];

    // supaya bisa langsung format di blade
    protected $casts = [
        'tanggal_pinjam'       => 'datetime',
        'tanggal_kembali'      => 'datetime',
        'tanggal_dikembalikan' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // 👇 penting buat cek di blade & controller
    public function qr()
    {
        return $this->hasOne(\App\Models\Qr::class, 'peminjaman_id');
    }
}
