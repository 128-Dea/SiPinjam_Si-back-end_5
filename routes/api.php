<?php

use Illuminate\Support\Facades\Route;

// API Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\Api\DendaController;
use App\Http\Controllers\Api\KeluhanController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\PengembalianController;
use App\Http\Controllers\Api\PerpanjanganController;
use App\Http\Controllers\Api\QrController;
use App\Http\Controllers\Api\RiwayatController;
use App\Http\Controllers\Api\SerahTerimaController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;

/*
|--------------------------------------------------------------------------
| AUTH TANPA TOKEN
|--------------------------------------------------------------------------
*/
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| ROUTE DENGAN TOKEN (SANCTUM)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // ===== AUTH =====
    Route::post('/logout', [AuthController::class, 'logout']);

    // ===== DASHBOARD =====
    Route::get('/dashboard',           [ApiDashboardController::class, 'index']);
    Route::get('/dashboard/admin',     [ApiDashboardController::class, 'admin']);
    Route::get('/dashboard/mahasiswa', [ApiDashboardController::class, 'mahasiswa']);

    // ===== CRUD RESOURCE (RESTful standar) =====
    Route::apiResources([
        'barang'        => BarangController::class,
        'kategori'      => KategoriController::class,
        'peminjaman'    => PeminjamanController::class,
        'pengguna'      => PenggunaController::class,
        'denda'         => DendaController::class,
        'pengembalian'  => PengembalianController::class,
        'perpanjangan'  => PerpanjanganController::class,
        'qr'            => QrController::class,
        'riwayat'       => RiwayatController::class,
        'serah-terima'  => SerahTerimaController::class,
        'service'       => ServiceController::class,
        // Notifikasi sengaja dipisah agar bisa tambah endpoint khusus di bawah
    ]);

    // ===== NOTIFIKASI (REST + endpoint khusus) =====
    Route::get('/notifikasi',                [NotifikasiController::class, 'index']);
    Route::post('/notifikasi',               [NotifikasiController::class, 'store']);
    Route::get('/notifikasi/{notifikasi}',   [NotifikasiController::class, 'show']);
    Route::patch('/notifikasi/{notifikasi}', [NotifikasiController::class, 'update']);
    Route::delete('/notifikasi/{notifikasi}',[NotifikasiController::class, 'destroy']);

    // Khusus tandai dibaca
    Route::patch('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'read']);
    Route::post('/notifikasi/read-all',           [NotifikasiController::class, 'readAll']);

    // ===== KELUHAN (list + detail + create) =====
    Route::get('/keluhan',               [KeluhanController::class, 'index']);
    Route::get('/keluhan/{keluhan}',     [KeluhanController::class, 'show']);
    Route::post('/keluhan',              [KeluhanController::class, 'store']); // controller validasi role=mahasiswa

    // ===== ROUTE TAMBAHAN UNTUK DATA PRIBADI MAHASISWA =====
    Route::get('/peminjaman/me',    [PeminjamanController::class, 'myPeminjaman']);
    Route::get('/pengembalian/me',  [PengembalianController::class, 'myPengembalian']);
    Route::get('/perpanjangan/me',  [PerpanjanganController::class, 'myPerpanjangan']);
});
