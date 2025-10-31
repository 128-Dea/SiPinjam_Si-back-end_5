@extends('layouts.app')

@section('title', 'Dashboard - Sistem Manajemen Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ringkasan Sistem</h5>
                <p class="card-text">Kelola data sistem manajemen barang dengan mudah.</p>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalBarang }}</h5>
                                <p class="card-text">Total Barang</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalKategori }}</h5>
                                <p class="card-text">Total Kategori</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalPeminjaman }}</h5>
                                <p class="card-text">Total Peminjaman</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
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
@endsection
