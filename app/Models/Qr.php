<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    protected $fillable = [
        'barang_id',
        'kode_qr',
        'data_qr'
    ];
}
