<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;

    protected $table = 'riwayat';

    protected $fillable = [
        'pengguna_id',
        'aksi',    // mis: 'peminjaman.create', 'pengembalian.store', 'perpanjangan.approve', 'serah_terima.create', 'denda.create'
        'detail',  // keterangan bebas
        'riwayatable_type',
        'riwayatable_id',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function riwayatable()
    {
        return $this->morphTo();
    }
}
