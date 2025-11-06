@extends('layouts.app')
@section('content')
<div class="container">
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <h3 class="mb-3">Detail Serah Terima #{{ $serahTerima->id }}</h3>

  <div class="card mb-3">
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-3">ID Peminjaman</dt>
        <dd class="col-sm-9">#{{ $serahTerima->peminjaman_id }}</dd>

        <dt class="col-sm-3">Pengguna Lama</dt>
        <dd class="col-sm-9">{{ $serahTerima->penggunaLama->nama ?? '-' }}</dd>

        <dt class="col-sm-3">Pengguna Baru</dt>
        <dd class="col-sm-9">{{ $serahTerima->penggunaBaru->nama ?? '-' }}</dd>

        <dt class="col-sm-3">Tanggal & Waktu</dt>
        <dd class="col-sm-9">{{ \Carbon\Carbon::parse($serahTerima->tanggal_serah_terima)->format('Y-m-d H:i') }}</dd>

        <dt class="col-sm-3">Catatan</dt>
        <dd class="col-sm-9">{{ $serahTerima->catatan ?? '-' }}</dd>
      </dl>
    </div>
  </div>

  <div class="card">
    <div class="card-body text-center">
      <h5 class="mb-3">QR Transaksi</h5>
      @if($serahTerima->qr_path)
        <img src="{{ asset('storage/'.$serahTerima->qr_path) }}" alt="QR Serah Terima" style="width:280px;height:280px;">
        <div class="mt-2">
          <a class="btn btn-outline-secondary btn-sm" href="{{ asset('storage/'.$serahTerima->qr_path) }}" download>Unduh QR</a>
        </div>
      @else
        <div class="text-muted">QR belum tersedia.</div>
      @endif
    </div>
  </div>

  <div class="mt-3">
    <a href="{{ route('serah-terima.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>
@endsection
