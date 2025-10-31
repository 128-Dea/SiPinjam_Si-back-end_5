<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai
    protected $table = 'barang';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'kategori',
        'lokasi',
        'status',
        'qr_code',
    ];

    // Kalau tabel kamu TIDAK punya kolom created_at & updated_at, uncomment baris ini:
    // public $timestamps = false;

    // Casting opsional
    protected $casts = [
        'nama_barang' => 'string',
        'deskripsi'   => 'string',
        'kategori'    => 'string',
        'lokasi'      => 'string',
        'status'      => 'string',
        'qr_code'     => 'string',
    ];

    // Relationship dengan Kategori (jika kategori adalah foreign key)
    // Jika kategori adalah string, hapus relationship ini
    // public function kategoriRelation()
    // {
    //     return $this->belongsTo(Kategori::class, 'kategori_id');
    // }
}
