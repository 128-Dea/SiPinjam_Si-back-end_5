<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerahTerima extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'pengguna_id',
        'tanggal_serah_terima',
        'catatan'
    ];
    public function peminjaman()
{
    return $this->belongsTo(Peminjaman::class);
}

public function pengguna()
{
    return $this->belongsTo(Pengguna::class);
}

}
