@extends('layouts.app')

@section('title', 'Perpanjangan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Daftar Perpanjangan</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(auth()->user()->role == 'mahasiswa')
                        <div class="mb-3">
                            <a href="{{ route('dashboard.perpanjangan.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Ajukan Perpanjangan
                            </a>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Barang</th>
                                    <th>Tanggal Perpanjangan</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    @if(auth()->user()->role == 'petugas')
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perpanjangan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->peminjaman->barang->nama ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_perpanjangan)->format('d/m/Y') }}</td>
                                        <td>{{ $item->alasan }}</td>
                                        <td>
                                            @if($item->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($item->status == 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($item->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        @if(auth()->user()->role == 'petugas')
                                            <td>
                                                @if($item->status == 'pending')
                                                    <a href="{{ route('dashboard.perpanjangan.edit', $item) }}"
                                                       class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Setujui/Tolak
                                                    </a>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role == 'petugas' ? 6 : 5 }}" class="text-center">
                                            Tidak ada data perpanjangan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $perpanjangan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
