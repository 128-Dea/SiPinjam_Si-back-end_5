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
                        @forelse($pengguna as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->nim }}</td>
                            <td>{{ $row->jurusan }}</td>
                            <td>
                                @if($row->role === 'petugas')
                                    <span class="badge bg-primary">Petugas</span>
                                @else
                                    <span class="badge bg-info">Mahasiswa</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dashboard.pengguna.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('dashboard.pengguna.destroy', $row->id) }}" method="POST" class="d-inline">
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

            {{-- pagination --}}
            {{ $pengguna->links() }}
        </div>
    </div>
</div>
@endsection
