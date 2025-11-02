@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Dashboard Mahasiswa</h3>
    <p class="text-muted mb-4">
        Selamat datang, {{ auth()->user()->nama ?? auth()->user()->email }} 👋
    </p>

    <div class="row g-3">
        {{-- Lihat Barang --}}
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('dashboard.barang.index') }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            📦
                        </div>
                        <h5 class="card-title mb-1">Lihat Barang</h5>
                        <p class="text-muted small mb-0">Daftar barang yang bisa dipinjam</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Peminjaman --}}
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('dashboard.peminjaman.index') }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            📝
                        </div>
                        <h5 class="card-title mb-1">Peminjaman</h5>
                        <p class="text-muted small mb-0">Riwayat / ajukan peminjaman</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Pengembalian (kalau kamu ada routenya) --}}
        <div class="col-md-3 col-sm-6">
            @if(Route::has('dashboard.pengembalian.index'))
            <a href="{{ route('dashboard.pengembalian.index') }}" class="text-decoration-none">
            @else
            <a href="#" class="text-decoration-none disabled">
            @endif
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            🔁
                        </div>
                        <h5 class="card-title mb-1">Pengembalian</h5>
                        <p class="text-muted small mb-0">Kembalikan barang yang dipinjam</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Perpanjangan --}}
        <div class="col-md-3 col-sm-6">
            @if(Route::has('dashboard.perpanjangan.index'))
            <a href="{{ route('dashboard.perpanjangan.index') }}" class="text-decoration-none">
            @else
            <a href="#" class="text-decoration-none disabled">
            @endif
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            ⏱️
                        </div>
                        <h5 class="card-title mb-1">Perpanjangan</h5>
                        <p class="text-muted small mb-0">Ajukan perpanjangan waktu</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- contoh: kalau mau tampilkan peminjaman terakhir --}}
    @isset($peminjamanTerakhir)
    <div class="mt-4">
        <h5>Peminjaman Terakhir</h5>
        <div class="card">
            <div class="card-body">
                <p class="mb-1"><strong>Barang:</strong> {{ $peminjamanTerakhir->barang->nama_barang ?? '-' }}</p>
                <p class="mb-1"><strong>Tanggal Pinjam:</strong> {{ $peminjamanTerakhir->tanggal_pinjam }}</p>
                <p class="mb-0"><strong>Status:</strong> {{ $peminjamanTerakhir->status }}</p>
            </div>
        </div>
    </div>
    @endisset
</div>
@endsection
