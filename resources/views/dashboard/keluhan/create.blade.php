@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Tambah Keluhan</h3>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('keluhan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">ID Peminjaman</label>
                    <select name="peminjaman_id" class="form-select @error('peminjaman_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Peminjaman --</option>
                        @foreach($peminjaman as $p)
                            <option value="{{ $p->id }}" {{ old('peminjaman_id')==$p->id?'selected':'' }}>
                                #{{ $p->id }} - {{ $p->barang->nama_barang ?? 'Barang' }}
                                ({{ $p->created_at?->format('d M Y H:i') }})
                            </option>
                        @endforeach
                    </select>
                    @error('peminjaman_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi Keluhan</label>
                    <textarea name="deskripsi_keluhan" rows="4" class="form-control @error('deskripsi_keluhan') is-invalid @enderror" required>{{ old('deskripsi_keluhan') }}</textarea>
                    @error('deskripsi_keluhan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Bukti (Foto/Video) <span class="text-danger">*</span></label>
                    <input type="file" name="bukti[]" class="form-control @error('bukti') is-invalid @enderror @error('bukti.*') is-invalid @enderror"
                           accept=".jpg,.jpeg,.png,.heic,.mp4,.mov,.avi,.mkv,.webm" multiple required>
                    <div class="form-text">Wajib minimal 1 file. Maks 50MB per file.</div>
                    @error('bukti') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    @error('bukti.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('keluhan.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary">Kirim Keluhan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
