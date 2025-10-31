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

// ==========================
// AUTH ROUTES
// ==========================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ==========================
// DASHBOARD ROUTES (WEB)
// ==========================
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {

    // Halaman utama dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Barang
    Route::get('/barang', [DashboardController::class, 'barang'])->name('barang.index');
    Route::get('/barang/create', [DashboardController::class, 'createBarang'])->name('barang.create');
    Route::post('/barang', [DashboardController::class, 'storeBarang'])->name('barang.store');
    Route::get('/barang/{id}/edit', [DashboardController::class, 'editBarang'])->name('barang.edit');
    Route::put('/barang/{id}', [DashboardController::class, 'updateBarang'])->name('barang.update');
    Route::delete('/barang/{id}', [DashboardController::class, 'destroyBarang'])->name('barang.destroy');

    // Kategori
    Route::get('/kategori', [DashboardController::class, 'kategori'])->name('kategori.index');
    Route::get('/kategori/create', [DashboardController::class, 'createKategori'])->name('kategori.create');
    Route::post('/kategori', [DashboardController::class, 'storeKategori'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [DashboardController::class, 'editKategori'])->name('kategori.edit');
    Route::put('/kategori/{id}', [DashboardController::class, 'updateKategori'])->name('kategori.update');
    Route::delete('/kategori/{id}', [DashboardController::class, 'destroyKategori'])->name('kategori.destroy');

    // Pengguna
    Route::get('/pengguna', [DashboardController::class, 'pengguna'])->name('pengguna.index');
    Route::get('/pengguna/create', [DashboardController::class, 'createPengguna'])->name('pengguna.create');
    Route::post('/pengguna', [DashboardController::class, 'storePengguna'])->name('pengguna.store');
    Route::get('/pengguna/{id}/edit', [DashboardController::class, 'editPengguna'])->name('pengguna.edit');
    Route::put('/pengguna/{id}', [DashboardController::class, 'updatePengguna'])->name('pengguna.update');
    Route::delete('/pengguna/{id}', [DashboardController::class, 'destroyPengguna'])->name('pengguna.destroy');

    // Peminjaman
    Route::get('/peminjaman', [DashboardController::class, 'peminjaman'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [DashboardController::class, 'createPeminjaman'])->name('peminjaman.create');
    Route::post('/peminjaman', [DashboardController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}/edit', [DashboardController::class, 'editPeminjaman'])->name('peminjaman.edit');
    Route::put('/peminjaman/{id}', [DashboardController::class, 'updatePeminjaman'])->name('peminjaman.update');
    Route::delete('/peminjaman/{id}', [DashboardController::class, 'destroyPeminjaman'])->name('peminjaman.destroy');
});

// ==========================
// API ROUTES (untuk Flutter / Mobile)
// ==========================
Route::apiResources([
    'barang' => BarangController::class,
    'kategori' => KategoriController::class,
    'denda' => DendaController::class,
    'keluhan' => KeluhanController::class,
    'notifikasi' => NotifikasiController::class,
    'peminjaman' => PeminjamanController::class,
    'pengembalian' => PengembalianController::class,
    'pengguna' => PenggunaController::class,
    'perpanjangan' => PerpanjanganController::class,
    'qr' => QrController::class,
    'riwayat' => RiwayatController::class,
    'serah-terima' => SerahTerimaController::class,
    'service' => ServiceController::class,
]);
