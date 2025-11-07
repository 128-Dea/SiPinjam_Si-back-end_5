<?php

use Illuminate\Support\Facades\Route;

// WEB Controllers (Blade)
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DendaController;          // Web (bukan Api\DendaController)
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\NotifikasiController;     // Web (bukan Api\NotifikasiController)
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PerpanjanganController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SerahTerimaController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Redirect awal
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Auth (WEB)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.post');

    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout (harus login)
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard (WEB)
|--------------------------------------------------------------------------
| Controller handle view berbeda berdasarkan role:
| - mahasiswa -> view('dashboard.mahasiswa.index')
| - petugas   -> view('dashboard.index')
*/
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {

    // Halaman utama dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // ===== Master & Transaksi (WEB) =====
    Route::resource('barang',        BarangController::class)->except(['show']);
    Route::resource('kategori',      KategoriController::class)->except(['show']);
    Route::resource('pengguna',      PenggunaController::class)->except(['show']);
    Route::resource('peminjaman',    PeminjamanController::class)->except(['show']);
    Route::resource('pengembalian',  PengembalianController::class)->except(['show']);
    Route::resource('perpanjangan',  PerpanjanganController::class)->except(['show']);
    Route::resource('denda',         DendaController::class)->except(['show']);
    Route::resource('keluhan',       KeluhanController::class)->except(['show']);
    Route::resource('notifikasi',    NotifikasiController::class)->except(['show']);
    Route::resource('qr',            QrController::class)->except(['show']);
    Route::resource('riwayat',       RiwayatController::class)->only(['index', 'show']);
    Route::resource('serah-terima',  SerahTerimaController::class)->except(['show']);
    Route::resource('service',       ServiceController::class)->except(['show']);
});
