@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Notifikasi</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Barang</th>
                    <th>Judul</th>
                    <th>Pesan</th>
                    <th>Tipe</th>
                    <th>Dibaca</th>
                    <th>Dibuat</th>
                </tr>
                </thead>
                <tbody>
                @forelse($notifikasis as $n)
                    <tr>
                        <td>{{ $n->id }}</td>
                        <td>{{ $n->barang_id ?? '-' }}</td>
                        <td>{{ $n->judul }}</td>
                        <td>{{ $n->pesan }}</td>
                        <td>
                            @php
                                $badge = match($n->tipe){
                                    'warning' => 'bg-warning',
                                    'error'   => 'bg-danger',
                                    default   => 'bg-secondary' // info
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $n->tipe }}</span>
                        </td>
                        <td>{!! $n->dibaca ? '<span class="badge bg-secondary">Ya</span>' : '<span class="badge bg-primary">Belum</span>' !!}</td>
                        <td>{{ $n->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Belum ada notifikasi.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $notifikasis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
