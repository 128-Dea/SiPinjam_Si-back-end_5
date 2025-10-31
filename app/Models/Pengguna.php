<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pengguna';

    /**
     * Kolom yang boleh diisi mass-assignment.
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'nim',
        'jurusan',
        'role',
    ];

    /**
     * Kolom sensitif yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut otomatis.
     */
    protected $casts = [
        // Laravel 10+ mendukung 'hashed' untuk otomatis hash password
        'password' => 'hashed',
    ];
}
