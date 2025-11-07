<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;

// Target model untuk morph map Riwayat
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Perpanjangan;
use App\Models\SerahTerima;
use App\Models\Denda;
use App\Models\Keluhan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pakai Bootstrap 5 untuk pagination Blade
        Paginator::useBootstrapFive();

        // Morph map untuk kolom polymorphic di Riwayat (riwayatable_type, riwayatable_id)
        Relation::enforceMorphMap([
            'peminjaman'   => Peminjaman::class,
            'pengembalian' => Pengembalian::class,
            'perpanjangan' => Perpanjangan::class,
            'serah_terima' => SerahTerima::class,
            'denda'        => Denda::class,
            'keluhan'      => Keluhan::class,   // <-- sesuai permintaan: tambahkan keluhan
        ]);
    }
}
