<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KeluhanLampiran extends Model
{
    protected $table = 'keluhan_lampiran';

    protected $fillable = [
        'keluhan_id', 'path', 'mime', 'size', 'original_name'
    ];

    protected $appends = ['url'];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class, 'keluhan_id');
    }

    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->path);
    }
}
