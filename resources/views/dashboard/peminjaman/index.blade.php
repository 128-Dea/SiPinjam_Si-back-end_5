@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Peminjaman</h2>
        @if(auth()->user()->role != 'mahasiswa')
        <a href="{{ route('dashboard.peminjaman.create') }}" class="btn btn-primary">Tambah Peminjaman</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pengguna</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $peminjaman)
                        @if(auth()->user()->role == 'mahasiswa' && $peminjaman->pengguna_id != auth()->id())
                            @continue
                        @endif
                        <tr>
                            <td>{{ $peminjaman->id }}</td>
                            <td>{{ $peminjaman->pengguna->nama ?? 'N/A' }}</td>
                            <td>{{ $peminjaman->barang->nama_barang ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $peminjaman->status == 'pending' ? 'warning' : ($peminjaman->status == 'dipinjam' ? 'primary' : 'success') }}">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>
                            <td>
                                @if(auth()->user()->role != 'mahasiswa')
                                <a href="{{ route('dashboard.peminjaman.edit', $peminjaman->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('dashboard.peminjaman.destroy', $peminjaman->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">Hapus</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data peminjaman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $peminjamans->links() }}
        </div>
    </div>
</div>
@endsection
