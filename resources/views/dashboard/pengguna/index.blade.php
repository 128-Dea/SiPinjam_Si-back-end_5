@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Pengguna</h2>
        <a href="{{ route('dashboard.pengguna.create') }}" class="btn btn-primary">Tambah Pengguna</a>
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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIM</th>
                            <th>Jurusan</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengguna as $pengguna)
                        <tr>
                            <td>{{ $pengguna->id }}</td>
                            <td>{{ $pengguna->nama }}</td>
                            <td>{{ $pengguna->email }}</td>
                            <td>{{ $pengguna->nim }}</td>
                            <td>{{ $pengguna->jurusan }}</td>
                            <td>
                                <span class="badge bg-{{ $pengguna->role == 'admin' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($pengguna->role) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('dashboard.pengguna.edit', $pengguna->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('dashboard.pengguna.destroy', $pengguna->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pengguna</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $pengguna->links() }}
        </div>
    </div>
</div>
@endsection
