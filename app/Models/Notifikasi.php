<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = [
        'pengguna_id',
        'judul',
        'pesan',
        'tipe',
        'dibaca'
    ];
}
