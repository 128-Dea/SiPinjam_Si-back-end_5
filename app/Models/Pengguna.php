<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // nama tabel
    protected $table = 'pengguna';

    // PK kamu pakai 'id' (default) → jadi gak usah set apa-apa

    // field yang boleh diisi mass assignment
    protected $fillable = [
        'nama',
        'email',
        'password',   // boleh plain, nanti di-cast ke hashed
        'nim',
        'jurusan',
        'role',       // 'mahasiswa' | 'petugas' | 'admin' (kalau kamu mau)
    ];

    // sembunyikan di JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // casting otomatis
    protected $casts = [
        // ini bikin password otomatis di-hash begitu diisi
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    // normalisasi sebelum simpan / update
    protected static function booted()
    {
        // pas create
        static::creating(function ($model) {
            // email jadi lowercase
            if (! empty($model->email)) {
                $model->email = strtolower($model->email);
            }

            // kalau role gak dikirim → default mahasiswa
            if (empty($model->role)) {
                $model->role = 'mahasiswa';
            }
        });

        // pas update
        static::updating(function ($model) {
            if (! empty($model->email)) {
                $model->email = strtolower($model->email);
            }
        });
    }

    // ==========================
    // RELASI (opsional, sesuaikan)
    // ==========================

    // misal: di tabel peminjaman kolomnya id_peminjam
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
