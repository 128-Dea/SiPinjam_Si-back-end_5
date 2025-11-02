@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Pengembalian</h2>
        @if(auth()->user()->role == 'mahasiswa')
            <a href="{{ route('dashboard.pengembalian.create') }}" class="btn btn-primary">
                Tambah Pengembalian
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Peminjaman</th>
                        <th>Nama Barang</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $pengembalian)
                        <tr>
                            <td>{{ $pengembalian->id }}</td>
                            <td>{{ $pengembalian->peminjaman->id ?? '-' }}</td>
                            <td>{{ $pengembalian->peminjaman->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $pengembalian->tanggal_pengembalian->format('d/m/Y H:i') }}</td>
                            <td>{{ $pengembalian->catatan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data pengembalian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($pengembalian instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                {{ $pengembalian->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
