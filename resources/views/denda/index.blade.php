@extends('layouts.app')
@section('content')
<div class="container">
  <h3 class="mb-3">Daftar Denda</h3>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th><th>Peminjaman</th><th>Jenis</th><th>Total</th><th>Status</th><th>Keterangan</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($dendas as $d)
            <tr>
              <td>{{ $d->id }}</td>
              <td>#{{ $d->peminjaman_id }}</td>
              <td><span class="badge {{ $d->jenis_denda=='hilang'?'bg-danger':'bg-warning' }}">{{ $d->jenis_denda }}</span></td>
              <td>Rp {{ number_format($d->total_denda,0,',','.') }}</td>
              <td><span class="badge {{ $d->status_pembayaran=='dibayar'?'bg-success':'bg-secondary' }}">{{ $d->status_pembayaran }}</span></td>
              <td>{{ $d->keterangan ?? '-' }}</td>
              <td>
                <a href="{{ route('dashboard.denda.edit',$d->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('dashboard.denda.destroy',$d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus denda ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-2">{{ $dendas->links() }}</div>
    </div>
  </div>
</div>
@endsection
