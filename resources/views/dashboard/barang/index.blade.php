@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Barang</h2>

        {{-- tombol tambah hanya untuk petugas --}}
        @if(auth()->user()->role === 'petugas')
            <a href="{{ route('dashboard.barang.create') }}" class="btn btn-primary">Tambah Barang</a>
        @endif
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
                            <th>Gambar</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            @if(auth()->user()->role === 'petugas')
                                <th style="width:160px">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $b)
                            <tr>
                                <td>{{ $b->id }}</td>

                                {{-- kolom gambar --}}
                                <td>
                                    @if($b->gambar)
                                        <img src="{{ asset('storage/'.$b->gambar) }}"
                                             alt="{{ $b->nama_barang }}"
                                             style="height:55px; width:55px; object-fit:cover; border-radius:6px;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>{{ $b->nama_barang }}</td>

                                {{-- kalau relasi kategori belum kamu set, pakai kolom biasa --}}
                                <td>
                                    {{ $b->kategori ?? (optional($b->kategoriRel)->nama_kategori ?? 'N/A') }}
                                </td>

                                <td>{{ $b->lokasi ?? '-' }}</td>

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

                                {{-- aksi hanya buat petugas --}}
                                @if(auth()->user()->role === 'petugas')
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
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'petugas' ? 7 : 6 }}" class="text-center">
                                    Tidak ada data barang
                                </td>
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
