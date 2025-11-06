<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $table = 'denda';

    protected $fillable = [
        'peminjaman_id',
        'jenis_denda',        // terlambat | hilang
        'total_denda',        // DECIMAL
        'status_pembayaran',  // belum_dibayar | dibayar
        'keterangan',
    ];

    protected $casts = [
        'total_denda' => 'decimal:2',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
}
