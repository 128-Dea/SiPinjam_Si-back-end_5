<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    protected $fillable = [
        'pengguna_id',
        'barang_id',
        'deskripsi_keluhan',
        'status'
    ];
}
