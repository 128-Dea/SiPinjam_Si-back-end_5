@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Edit Barang</h3></div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dashboard.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text"
                                   class="form-control @error('nama_barang') is-invalid @enderror"
                                   id="nama_barang"
                                   name="nama_barang"
                                   value="{{ old('nama_barang', $barang->nama_barang) }}"
                                   required>
                            @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                      id="deskripsi"
                                      name="deskripsi"
                                      rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                            @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select @error('kategori') is-invalid @enderror"
                                    id="kategori"
                                    name="kategori"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->nama_kategori }}"
                                        {{ old('kategori', $barang->kategori) == $kat->nama_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text"
                                   class="form-control @error('lokasi') is-invalid @enderror"
                                   id="lokasi"
                                   name="lokasi"
                                   value="{{ old('lokasi', $barang->lokasi) }}">
                            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="tersedia" {{ old('status', $barang->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="dipinjam" {{ old('status', $barang->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="rusak"    {{ old('status', $barang->status) == 'rusak'    ? 'selected' : '' }}>Rusak</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- tampilkan gambar lama --}}
                        @if($barang->gambar)
                            <div class="mb-3">
                                <label class="form-label d-block">Gambar saat ini</label>
                                <img src="{{ asset('storage/'.$barang->gambar) }}" alt="{{ $barang->nama_barang }}" style="max-height: 120px">
                            </div>
                        @endif

                        {{-- ganti gambar --}}
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Ganti Gambar (opsional)</label>
                            <input type="file"
                                   class="form-control @error('gambar') is-invalid @enderror"
                                   id="gambar"
                                   name="gambar"
                                   accept="image/*">
                            <small class="text-muted d-block">Kosongkan kalau tidak ingin ganti.</small>
                            @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard.barang.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
