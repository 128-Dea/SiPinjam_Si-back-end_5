@extends('layouts.app')
@section('content')
<div class="container">
  <h3 class="mb-3">Riwayat Aktivitas</h3>
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Entitas</th>
            <th>ID Ref</th>
            <th>Pengguna</th>
            <th>Aksi</th>
            <th>Detail</th>
            <th>Waktu</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($riwayats as $r)
          @php
            $entitas = $r->riwayatable ? class_basename($r->riwayatable_type) : '-';
            $refId   = $r->riwayatable_id ?? '-';
          @endphp
          <tr>
            <td>{{ $r->id }}</td>
            <td>{{ $entitas }}</td>
            <td>{{ $refId }}</td>
            <td>{{ $r->pengguna->nama ?? '-' }}</td>
            <td><code>{{ $r->aksi }}</code></td>
            <td>{{ $r->detail ?? '-' }}</td>
            <td>{{ $r->created_at?->format('Y-m-d H:i') }}</td>
            <td>
              <a href="{{ route('riwayat.show', $r->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center text-muted">Belum ada riwayat.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-2">{{ $riwayats->links() }}</div>
    </div>
  </div>
</div>
@endsection
