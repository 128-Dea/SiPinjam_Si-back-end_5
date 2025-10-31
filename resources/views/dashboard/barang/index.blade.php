@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Barang</h2>
        <a href="{{ route('dashboard.barang.create') }}" class="btn btn-primary">Tambah Barang</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $b)
                            <tr>
                                <td>{{ $b->id }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>{{ optional($b->kategori)->nama_kategori ?? 'N/A' }}</td>
                                <td>{{ $b->lokasi }}</td>
                                <td>
                                    @php
                                        $map = [
                                            'tersedia' => 'success',
                                            'dipinjam' => 'warning',
                                            'rusak'    => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $map[$b->status] ?? 'secondary' }}">
                                        {{ ucfirst($b->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.barang.edit', $b->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('dashboard.barang.destroy', $b->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data barang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $barang->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
