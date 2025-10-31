<?php

use Illuminate\Support\Facades\Route;
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

// ================= AUTH ROUTES =================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ================= API CRUD ROUTES =================
Route::apiResources([
    'barang'          => BarangController::class,
    'kategori'        => KategoriController::class,
    'peminjaman'      => PeminjamanController::class,
    'pengguna'        => PenggunaController::class,
    'denda'           => DendaController::class,
    'keluhan'         => KeluhanController::class,
    'notifikasi'      => NotifikasiController::class,
    'pengembalian'    => PengembalianController::class,
    'perpanjangan'    => PerpanjanganController::class,
    'qr'              => QrController::class,
    'riwayat'         => RiwayatController::class,
    'serah-terima'    => SerahTerimaController::class,
    'service'         => ServiceController::class,
]);
