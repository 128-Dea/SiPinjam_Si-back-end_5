@extends('layouts.app')
@section('content')
<div class="container">
  <h3 class="mb-3">Detail Riwayat #{{ $riwayat->id }}</h3>

  <div class="card">
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-3">Entitas</dt>
        <dd class="col-sm-9">{{ $riwayat->riwayatable ? class_basename($riwayat->riwayatable_type) : '-' }}</dd>

        <dt class="col-sm-3">ID Referensi</dt>
        <dd class="col-sm-9">{{ $riwayat->riwayatable_id ?? '-' }}</dd>

        <dt class="col-sm-3">Pengguna</dt>
        <dd class="col-sm-9">{{ $riwayat->pengguna->nama ?? '-' }}</dd>

        <dt class="col-sm-3">Aksi</dt>
        <dd class="col-sm-9"><code>{{ $riwayat->aksi }}</code></dd>

        <dt class="col-sm-3">Detail</dt>
        <dd class="col-sm-9">{{ $riwayat->detail ?? '-' }}</dd>

        <dt class="col-sm-3">Waktu</dt>
        <dd class="col-sm-9">{{ $riwayat->created_at?->format('Y-m-d H:i:s') }}</dd>
      </dl>
    </div>
  </div>

  <div class="mt-3">
    <a href="{{ route('riwayat.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>
@endsection
