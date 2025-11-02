<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Perpanjangan extends Model
{
    protected $table = 'perpanjangan';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_perpanjangan',
        'alasan',
        'status'
    ];

    protected $dates = [
        'tanggal_perpanjangan',
        'created_at',
        'updated_at'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
