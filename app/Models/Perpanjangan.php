<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perpanjangan extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'tanggal_perpanjangan',
        'alasan',
        'status'
    ];
    public function peminjaman()
{
    return $this->belongsTo(Peminjaman::class);
}

}
