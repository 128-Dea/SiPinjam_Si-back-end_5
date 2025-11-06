@extends('layouts.app')
@section('content')
<div class="container">
  <h3 class="mb-3">Serah Terima</h3>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

  @if(auth()->user()->role === 'mahasiswa')
    <a class="btn btn-primary mb-3" href="{{ route('dashboard.serah-terima.create') }}">Buat Serah Terima</a>
  @endif

  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>ID Peminjaman</th>
            <th>Pengguna Lama</th>
            <th>Pengguna Baru</th>
            <th>Tanggal & Waktu</th>
            <th>Catatan</th>
            <th>QR</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($serahTerimas as $s)
          <tr>
            <td>{{ $s->id }}</td>
            <td>#{{ $s->peminjaman_id }}</td>
            <td>{{ $s->penggunaLama->nama ?? '-' }}</td>
            <td>{{ $s->penggunaBaru->nama ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($s->tanggal_serah_terima)->format('Y-m-d H:i') }}</td>
            <td>{{ $s->catatan ?? '-' }}</td>
            <td>
              @if($s->qr_path)
                <img src="{{ asset('storage/'.$s->qr_path) }}" alt="QR" style="width:70px;height:70px;">
              @else
                -
              @endif
            </td>
            <td>
              <a href="{{ route('dashboard.serah-terima.show',$s->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center text-muted">Belum ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-2">{{ $serahTerimas->links() }}</div>
    </div>
  </div>
</div>
@endsection
