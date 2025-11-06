<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi'; // <— penting: bukan notifikasis

    protected $fillable = [
        'pengguna_id',
        'barang_id',
        'judul',
        'pesan',
        'tipe',        // info|warning|error
        'role_target', // mahasiswa|petugas
        'dibaca',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // === Scopes cepat ===
    public function scopeForMahasiswa($q, int $userId)
    {
        return $q->where('role_target', 'mahasiswa')
                 ->where('pengguna_id', $userId);
    }

    public function scopeForPetugas($q)
    {
        return $q->where('role_target', 'petugas');
    }

    public function scopeUnread($q)
    {
        return $q->where('dibaca', false);
    }
}
