@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajukan Perpanjangan</h2>

    <form action="{{ route('dashboard.perpanjangan.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="peminjaman_id" class="form-label">Pilih Peminjaman</label>
            <select name="peminjaman_id" class="form-select" required>
                <option value="">-- Pilih ID Peminjaman --</option>
                @foreach($peminjaman as $p)
                    <option value="{{ $p->id }}">
                        #{{ $p->id }} - {{ $p->barang->nama_barang ?? 'Barang' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="alasan" class="form-label">Alasan Perpanjangan</label>
            <textarea name="alasan" class="form-control" rows="3" required></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('dashboard.perpanjangan.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Ajukan</button>
        </div>
    </form>
</div>
@endsection
