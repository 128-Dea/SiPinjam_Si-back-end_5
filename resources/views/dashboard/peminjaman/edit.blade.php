@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Peminjaman</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.peminjaman.update', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if(auth()->user()->role == 'mahasiswa')
                        <div class="mb-3">
                            <label for="pengguna_id" class="form-label">Pengguna</label>
                            <select class="form-select @error('pengguna_id') is-invalid @enderror" id="pengguna_id" name="pengguna_id" required>
                                <option value="">Pilih Pengguna</option>
                                @foreach($pengguna as $p)
                                    <option value="{{ $p->id }}" {{ old('pengguna_id', $peminjaman->pengguna_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->email }})</option>
                                @endforeach
                            </select>
                            @error('pengguna_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Barang</label>
                            <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $b)
                                    <option value="{{ $b->id }}" {{ old('barang_id', $peminjaman->barang_id) == $b->id ? 'selected' : '' }}>{{ $b->nama_barang }} ({{ $b->lokasi }})</option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam) }}" required>
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror" id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali) }}" required>
                            @error('tanggal_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_dikembalikan" class="form-label">Tanggal Dikembalikan</label>
                            <input type="date" class="form-control @error('tanggal_dikembalikan') is-invalid @enderror" id="tanggal_dikembalikan" name="tanggal_dikembalikan" value="{{ old('tanggal_dikembalikan', $peminjaman->tanggal_dikembalikan) }}">
                            @error('tanggal_dikembalikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3">{{ old('catatan', $peminjaman->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                @if(auth()->user()->role == 'mahasiswa')
                                <option value="pending" {{ old('status', $peminjaman->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="dipinjam" {{ old('status', $peminjaman->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                @else
                                <option value="pending" {{ old('status', $peminjaman->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ old('status', $peminjaman->status) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ old('status', $peminjaman->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="dipinjam" {{ old('status', $peminjaman->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                @endif
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard.peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
