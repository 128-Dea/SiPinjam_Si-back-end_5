<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerahTerima extends Model
{
    protected $table = 'serah_terima';

    protected $fillable = [
        'peminjaman_id',
        'pengguna_lama_id',
        'pengguna_baru_id',
        'tanggal_serah_terima', // datetime
        'catatan',
        'qr_path',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function penggunaLama()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_lama_id');
    }

    public function penggunaBaru()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_baru_id');
    }
}
