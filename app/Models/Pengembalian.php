<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengembalian',
        'kondisi_barang',
        'denda',
        'catatan'
    ];
    // App\Models\Pengembalian.php
public function peminjaman()
{
    return $this->belongsTo(Peminjaman::class);
}

}
