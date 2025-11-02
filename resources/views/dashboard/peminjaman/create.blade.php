@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Tambah Peminjaman Baru</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.peminjaman.store') }}" method="POST">
                        @csrf

                        {{-- kalau PETUGAS yang input, boleh pilih pengguna --}}
                        @if(auth()->user()->role != 'mahasiswa')
                            <div class="mb-3">
                                <label for="pengguna_id" class="form-label">Pengguna</label>
                                <select class="form-select @error('pengguna_id') is-invalid @enderror" id="pengguna_id" name="pengguna_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    @foreach($pengguna as $p)
                                        <option value="{{ $p->id }}" {{ old('pengguna_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }} ({{ $p->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('pengguna_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            {{-- kalau mahasiswa, pakai user login --}}
                            <input type="hidden" name="pengguna_id" value="{{ auth()->id() }}">
                        @endif

                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Barang</label>
                            <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $b)
                                    <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_barang }} ({{ $b->lokasi }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TANGGAL + JAM PINJAM --}}
                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal & Jam Pinjam</label>
                            <input
                                type="datetime-local"
                                class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                id="tanggal_pinjam"
                                name="tanggal_pinjam"
                                value="{{ old('tanggal_pinjam') ? \Carbon\Carbon::parse(old('tanggal_pinjam'))->format('Y-m-d\TH:i') : '' }}"
                                required>
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TANGGAL + JAM KEMBALI --}}
                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal & Jam Kembali</label>
                            <input
                                type="datetime-local"
                                class="form-control @error('tanggal_kembali') is-invalid @enderror"
                                id="tanggal_kembali"
                                name="tanggal_kembali"
                                value="{{ old('tanggal_kembali') ? \Carbon\Carbon::parse(old('tanggal_kembali'))->format('Y-m-d\TH:i') : '' }}"
                                required>
                            @error('tanggal_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        @if(auth()->user()->role != 'mahasiswa')
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending"     {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="disetujui"   {{ old('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="dipinjam"    {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="dikembalikan"{{ old('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="status" value="pending">
                        @endif

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard.peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
