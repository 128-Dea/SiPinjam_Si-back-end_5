<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // nama tabelnya memang 'pengguna'
    protected $table = 'pengguna';

    // 👇 TIDAK perlu set primaryKey karena di DB kamu kolomnya 'id'
    // protected $primaryKey = 'id_pengguna';

    // field yang boleh diisi mass-assignment
    protected $fillable = [
        'nama',
        'email',
        'password',   // akan di-hash otomatis oleh casts
        'role',
    ];

    // sembunyikan saat di-return JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // otomatis hash password + cast datetime
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // normalisasi data sebelum simpan
    protected static function booted()
    {
        static::creating(function ($model) {
            if (! empty($model->email)) {
                $model->email = strtolower($model->email);
            }
            if (empty($model->role)) {
                $model->role = 'mahasiswa';
            }
        });

        static::updating(function ($model) {
            if (! empty($model->email)) {
                $model->email = strtolower($model->email);
            }
        });
    }

    // ========== RELASI (silakan sesuaikan nama FK di tabel kamu) ==========

    // contoh: kalau di tabel peminjaman kolomnya 'id_peminjam'
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_peminjam', 'id');
    }

    public function keluhan()
    {
        return $this->hasMany(Keluhan::class, 'id_pengguna', 'id');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_pengguna', 'id');
    }

    public function serahTerimaAsal()
    {
        return $this->hasMany(SerahTerima::class, 'id_pengguna_asal', 'id');
    }

    public function serahTerimaTujuan()
    {
        return $this->hasMany(SerahTerima::class, 'id_pengguna_tujuan', 'id');
    }

    public function logs()
    {
        return $this->hasMany(LogAktivitas::class, 'id_pengguna', 'id');
    }
}
