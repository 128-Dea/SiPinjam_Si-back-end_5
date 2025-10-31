<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'barang_id',
        'tanggal_service',
        'deskripsi_service',
        'biaya',
        'status'
    ];
}
