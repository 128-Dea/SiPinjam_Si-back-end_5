@extends('layouts.app')

@section('title', 'Ubah Status Perpanjangan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Ubah Status Perpanjangan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('dashboard.perpanjangan.update', $perpanjangan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Barang</label>
                                    <input type="text" class="form-control" value="{{ $perpanjangan->peminjaman->barang->nama ?? 'N/A' }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Perpanjangan</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($perpanjangan->tanggal_perpanjangan)->format('d/m/Y') }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alasan</label>
                                    <textarea class="form-control" rows="3" readonly>{{ $perpanjangan->alasan }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status Persetujuan</label>
                                    <select name="status" class="form-select" required>
                                        <option value="disetujui" {{ $perpanjangan->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="ditolak" {{ $perpanjangan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('dashboard.perpanjangan.index') }}" class="btn btn-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
