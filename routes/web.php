<?php

use Illuminate\Support\Facades\Route;
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

// ============== AUTH ROUTES ============

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ======== DASHBOARD ROUTES (WEB) ===========
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Barang
    Route::resource('barang', BarangController::class)->except(['show']);

    // Kategori
    Route::resource('kategori', KategoriController::class)->except(['show']);

    // Pengguna
    Route::resource('pengguna', PenggunaController::class)->except(['show']);

    // Peminjaman
    Route::resource('peminjaman', PeminjamanController::class)->except(['show']);

    // Denda
    Route::resource('denda', DendaController::class)->except(['show']);

    // Keluhan
    Route::resource('keluhan', KeluhanController::class)->except(['show']);

    // Notifikasi
    Route::resource('notifikasi', NotifikasiController::class)->except(['show']);

    // Pengembalian
    Route::resource('pengembalian', PengembalianController::class)->except(['show']);

    // Perpanjangan
    Route::resource('perpanjangan', PerpanjanganController::class)->except(['show']);

    // QR
    Route::resource('qr', QrController::class)->except(['show']);

    // Riwayat
    Route::resource('riwayat', RiwayatController::class)->except(['show']);

    // Serah Terima
    Route::resource('serah-terima', SerahTerimaController::class)->except(['show']);

    // Service
    Route::resource('service', ServiceController::class)->except(['show']);
});
