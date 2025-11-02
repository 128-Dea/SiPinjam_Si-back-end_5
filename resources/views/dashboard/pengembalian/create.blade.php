@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Pengembalian</h2>

    <form action="{{ route('dashboard.pengembalian.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="peminjaman_id" class="form-label">Pilih Peminjaman</label>
            <select name="peminjaman_id" id="peminjaman_id" class="form-select" required>
                <option value="">-- pilih ID peminjaman --</option>
                @foreach($peminjaman as $p)
                    <option value="{{ $p->id }}">
                        #{{ $p->id }} - {{ $p->barang->nama_barang ?? 'Barang' }}
                    </option>
                @endforeach
            </select>
            @error('peminjaman_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan (opsional)</label>
            <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('dashboard.pengembalian.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Pengembalian</button>
        </div>
    </form>
</div>
@endsection
