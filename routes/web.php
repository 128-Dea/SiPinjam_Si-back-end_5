<?php

use Illuminate\Support\Facades\Route;

// WEB controller (bukan API)
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\NotifikasiController;
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
| Biar kalau buka http://127.0.0.1:8000 langsung ke login.
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Auth (WEB)
|--------------------------------------------------------------------------
| Ini form Blade yang kamu pakai tadi.
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.post');
});

// logout harus sudah login
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard (WEB)
|--------------------------------------------------------------------------
| Di controller-mu tadi sudah:
| - kalau role = mahasiswa → return view('dashboard.mahasiswa.index')
| - kalau role = petugas   → return view('dashboard.index')
*/
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {

    // halaman utama dashboard (role-based view)
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // ===== BARANG (web) =====
    // ini pakai App\Http\Controllers\BarangController (bukan API)
    Route::resource('barang', BarangController::class)->except(['show']);

    // ===== KATEGORI =====
    Route::resource('kategori', KategoriController::class)->except(['show']);

    // ===== PENGGUNA =====
    Route::resource('pengguna', PenggunaController::class)->except(['show']);

    // ===== PEMINJAMAN =====
    Route::resource('peminjaman', PeminjamanController::class)->except(['show']);

    // ===== DENDA =====
    Route::resource('denda', DendaController::class)->except(['show']);

    // ===== KELUHAN =====
    Route::resource('keluhan', KeluhanController::class)->except(['show']);

    // ===== NOTIFIKASI =====
    Route::resource('notifikasi', NotifikasiController::class)->except(['show']);

    // ===== PENGEMBALIAN =====
    Route::resource('pengembalian', PengembalianController::class)->except(['show']);

    // ===== PERPANJANGAN =====
    Route::resource('perpanjangan', PerpanjanganController::class)->except(['show']);

    // ===== QR =====
    Route::resource('qr', QrController::class)->except(['show']);

    // ===== RIWAYAT =====
    Route::resource('riwayat', RiwayatController::class)->except(['show']);

    // ===== SERAH TERIMA =====
    Route::resource('serah-terima', SerahTerimaController::class)->except(['show']);

    // ===== SERVICE =====
    Route::resource('service', ServiceController::class)->except(['show']);
});
