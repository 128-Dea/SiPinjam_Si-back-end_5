<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Manajemen Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard.index') }}">Dashboard</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Selamat Datang, {{ Auth::user()->nama ?? 'Admin' }}</span>
                <a class="nav-link" href="{{ route('logout') }}">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-2">
                <div class="list-group">
                    @if(auth()->user()->role != 'mahasiswa')
                    <a href="{{ route('dashboard.index') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                    <a href="{{ route('dashboard.barang.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-box"></i> Barang</a>
                    <a href="{{ route('dashboard.kategori.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-tags"></i> Kategori</a>
                    <a href="{{ route('dashboard.pengguna.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-people"></i> Pengguna</a>
                    @endif
                    <a href="{{ route('dashboard.peminjaman.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-arrow-right-circle"></i> Peminjaman</a>
                </div>
            </div>

            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Sistem</h5>
                        <p class="card-text">Kelola data sistem manajemen barang dengan mudah.</p>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card text-center bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $totalBarang }}</h5>
                                        <p class="card-text">Total Barang</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $totalKategori }}</h5>
                                        <p class="card-text">Total Kategori</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $totalPeminjaman }}</h5>
                                        <p class="card-text">Total Peminjaman</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $totalPengguna }}</h5>
                                        <p class="card-text">Total Pengguna</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
