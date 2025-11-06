@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Daftar Keluhan</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(auth()->user()->role === 'mahasiswa')
        <a href="{{ route('dashboard.keluhan.create') }}" class="btn btn-primary mb-3">Tambah Keluhan</a>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID Keluhan</th>
                        <th>Deskripsi</th>
                        <th>ID Peminjaman</th>
                        @if(auth()->user()->role !== 'mahasiswa')
                            <th>ID Pengguna</th>
                        @endif
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($keluhans as $k)
                    <tr>
                        <td>{{ $k->id }}</td>
                        <td style="max-width:360px">{{ $k->deskripsi_keluhan }}</td>
                        <td>{{ $k->peminjaman_id }}</td>
                        @if(auth()->user()->role !== 'mahasiswa')
                            <td>{{ $k->pengguna_id }}</td>
                        @endif
                        <td>
                            @foreach($k->lampiran as $f)
                                @php
                                    $isVideo = Str::startsWith($f->mime, 'video');
                                @endphp
                                @if($isVideo)
                                    <video src="{{ $f->url }}" controls style="max-width:160px;max-height:120px"></video>
                                @else
                                    <a href="{{ $f->url }}" target="_blank">
                                        <img src="{{ $f->url }}" alt="bukti" style="max-width:120px;max-height:90px;border-radius:6px;margin-right:6px;">
                                    </a>
                                @endif
                            @endforeach
                        </td>
                        <td><span class="badge text-bg-secondary">{{ $k->status }}</span></td>
                        <td>{{ $k->created_at?->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $keluhans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
