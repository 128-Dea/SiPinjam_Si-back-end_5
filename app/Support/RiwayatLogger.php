<?php

namespace App\Support;

use App\Models\Riwayat;
use Illuminate\Support\Facades\Auth;

class RiwayatLogger
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $entity  // objek yang baru dibuat/diubah (Denda, Peminjaman, dst)
     * @param string $aksi                                 // contoh: 'peminjaman.create'
     * @param string|null $detail                          // keterangan tambahan
     * @param int|null $penggunaId                         // default: Auth::id()
     */
    public static function log($entity, string $aksi, ?string $detail = null, ?int $penggunaId = null): void
    {
        $penggunaId = $penggunaId ?? (Auth::check() ? Auth::id() : null);

        Riwayat::create([
            'pengguna_id'      => $penggunaId,
            'aksi'             => $aksi,
            'detail'           => $detail,
            'riwayatable_type' => get_class($entity),
            'riwayatable_id'   => $entity->getKey(),
        ]);
    }
}
