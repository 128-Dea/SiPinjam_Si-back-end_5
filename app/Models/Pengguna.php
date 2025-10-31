<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

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
     * Sembunyikan kolom sensitif saat di-serialize.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected $casts = [
        'password' => 'hashed',
        // 'email_verified_at' => 'datetime',
    ];
}
