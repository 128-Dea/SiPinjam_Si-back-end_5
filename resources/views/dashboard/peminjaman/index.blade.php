@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Peminjaman</h2>
        @if(auth()->user()->role == 'mahasiswa')
            <a href="{{ route('dashboard.peminjaman.create') }}" class="btn btn-primary">Ajukan Peminjaman</a>
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
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pengguna</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>QR</th>
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
                                <td>{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d/m/Y H:i') : '-' }}</td>
                                <td>{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $peminjaman->status == 'pending' ? 'warning' : ($peminjaman->status == 'disetujui' ? 'success' : ($peminjaman->status == 'ditolak' ? 'danger' : ($peminjaman->status == 'dipinjam' ? 'primary' : ($peminjaman->status == 'dikembalikan' ? 'success' : 'danger')))) }}">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($peminjaman->qr)
                                        <div class="small">
                                            <strong>ID QR:</strong> {{ $peminjaman->qr->id }} <br>
                                            <strong>Kode:</strong> {{ $peminjaman->qr->kode_qr }} <br>
                                            <strong>Tipe:</strong> {{ $peminjaman->qr->tipe }} <br>

                                            @if($peminjaman->qr->tipe === 'peminjaman')
                                                <strong>ID Peminjaman:</strong> {{ $peminjaman->id }}
                                            @elseif($peminjaman->qr->tipe === 'serah_terima')
                                                <strong>ID Serah Terima:</strong> {{ $peminjaman->qr->serah_terima_id }}
                                            @endif
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Belum ada QR</span>
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->user()->role != 'mahasiswa')
                                        <a href="{{ route('dashboard.peminjaman.edit', $peminjaman->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('dashboard.peminjaman.destroy', $peminjaman->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        @if($peminjaman->status == 'pending')
                                            <span class="text-muted">Menunggu Persetujuan</span>
                                        @elseif($peminjaman->status == 'disetujui')
                                            <span class="text-success">Disetujui</span>
                                        @elseif($peminjaman->status == 'ditolak')
                                            <span class="text-danger">Ditolak</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data peminjaman</td>
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
