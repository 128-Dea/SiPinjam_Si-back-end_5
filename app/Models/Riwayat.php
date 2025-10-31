<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;

    // Tentukan nama tabel (jaga-jaga kalau bukan plural otomatis)
    protected $table = 'riwayat';

    protected $fillable = [
        'pengguna_id',
        'aksi',
        'detail',
    ];

    
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
