<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    protected $table = 'qr';

    protected $fillable = [
        'barang_id',
        'peminjaman_id',
        'serah_terima_id',
        'tipe',
        'kode_qr',
        'data_qr',
    ];

    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class, 'barang_id');
    }

    public function peminjaman()
    {
        return $this->belongsTo(\App\Models\Peminjaman::class, 'peminjaman_id');
    }

    // opsional, kalau kamu nanti punya tabel serah terima
    public function serahTerima()
    {
        return $this->belongsTo(\App\Models\SerahTerima::class, 'serah_terima_id');
    }
}
