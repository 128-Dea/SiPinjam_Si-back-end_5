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

    // Relasi ke Pengguna
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
