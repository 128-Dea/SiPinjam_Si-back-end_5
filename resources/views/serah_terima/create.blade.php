@extends('layouts.app')
@section('content')
<div class="container">
  <h3 class="mb-3">Buat Serah Terima</h3>
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="POST" action="{{ route('serah-terima.store') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Peminjaman (milik Anda)</label>
      <select name="peminjaman_id" class="form-select" required>
        <option value="">-- Pilih --</option>
        @foreach($peminjamans as $p)
          <option value="{{ $p->id }}" @selected(old('peminjaman_id')==$p->id)>
            #{{ $p->id }} - {{ $p->barang->nama_barang ?? 'Barang' }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Pengguna Baru (Mahasiswa)</label>
      <select name="pengguna_baru_id" class="form-select" required>
        <option value="">-- Pilih --</option>
        @foreach($mahasiswas as $m)
          <option value="{{ $m->id }}" @selected(old('pengguna_baru_id')==$m->id)>{{ $m->nama }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Tanggal & Waktu</label>
      <input type="datetime-local" name="tanggal_serah_terima" class="form-control" value="{{ old('tanggal_serah_terima') }}">
      <div class="form-text">Kosongkan jika ingin otomatis waktu sekarang.</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Catatan</label>
      <textarea name="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
    </div>

    <button class="btn btn-primary">Simpan & Tampilkan QR</button>
    <a href="{{ route('serah-terima.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection
