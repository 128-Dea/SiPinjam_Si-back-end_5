<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'kategori',
        'lokasi',
        'status',
        'qr_code',
    ];


    // Casting opsional
    protected $casts = [
        'nama_barang' => 'string',
        'deskripsi'   => 'string',
        'kategori'    => 'string',
        'lokasi'      => 'string',
        'status'      => 'string',
        'qr_code'     => 'string',
    ];

   
}
