<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'jumlah_denda',
        'alasan',
        'status'
    ];
}
