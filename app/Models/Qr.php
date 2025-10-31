<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    use HasFactory;

    protected $table = 'qr'; // pastikan sesuai dengan nama tabel di database

    protected $fillable = [
        'barang_id',
        'kode_qr',
        'data_qr',
    ];

    /**
     * Relasi ke model Barang.
     * Setiap QR dimiliki oleh satu Barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
