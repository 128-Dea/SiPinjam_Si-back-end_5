<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Sistem Manajemen Barang')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')

    <style>
        html, body { height: 100%; }
        .sidebar { position: sticky; top: 1rem; }
        .list-group-item.disabled { pointer-events: none; opacity: .6; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('dashboard.index') }}">
                Sistem Manajemen Barang
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto navbar-nav align-items-lg-center">
                    @auth
                        <span class="navbar-text me-2">
                            <i class="bi bi-person-circle me-1"></i>
                            Selamat datang, <strong>{{ Auth::user()->nama ?? 'User' }}</strong>
                        </span>

                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                               Akun
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="px-3 py-1">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-decoration-none p-0">
                                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth

                    @guest
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">Daftar</a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 mb-3 mb-md-0">
                <div class="list-group sidebar">
                    <a href="{{ route('dashboard.index') }}"
                       class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                        <i class="bi bi-house me-1"></i> Dashboard
                    </a>

                    <a href="{{ route('dashboard.barang.index') }}"
                       class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.barang*') ? 'active' : '' }}">
                        <i class="bi bi-box me-1"></i> Barang
                    </a>

                    <a href="{{ route('dashboard.kategori.index') }}"
                       class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.kategori*') ? 'active' : '' }}">
                        <i class="bi bi-tags me-1"></i> Kategori
                    </a>

                    <a href="{{ route('dashboard.pengguna.index') }}"
                       class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.pengguna*') ? 'active' : '' }}">
                        <i class="bi bi-people me-1"></i> Pengguna
                    </a>

                    <a href="{{ route('dashboard.peminjaman.index') }}"
                       class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.peminjaman*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-right-circle me-1"></i> Peminjaman
                    </a>

                    <!-- Menu lain yang belum aktif -->
                    <a href="#" class="list-group-item list-group-item-action disabled"><i class="bi bi-arrow-left-circle me-1"></i> Pengembalian</a>
                    <a href="#" class="list-group-item list-group-item-action disabled"><i class="bi bi-cash me-1"></i> Denda</a>
                    <a href="#" class="list-group-item list-group-item-action disabled"><i class="bi bi-exclamation-triangle me-1"></i> Keluhan</a>
                    <a href="#" class="list-group-item list-group-item-action disabled"><i class="bi bi-bell me-1"></i> Notifikasi</a>
                    <a href="#" class="list-group-item list-group-item-action disabled"><i class="bi bi-calendar-plus me-1"></i> Perpanjangan</a>
                    <a href="#" class="list-group-item list-group-item-action disabled"><i class="bi bi-qr-code me-1"></i> QR Code</a>
                    <a href="#" class="list-group-item list-group-item-action disabled"><i class="bi bi-clock-history me-1"></i> Riwayat</a>
                    <a href="#" class="list-group-item list-group-item-action disabled"><i class="bi bi-handshake me-1"></i> Serah Terima</a>
                    <a href="#" class="list-group-item list-group-item-action disabled"><i class="bi bi-tools me-1"></i> Service</a>
                </div>
            </div>

            <!-- Isi Konten -->
            <div class="col-md-10">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
