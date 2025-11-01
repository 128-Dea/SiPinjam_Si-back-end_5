<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori; // ⬅️ ini penting
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->role === 'mahasiswa') {
            $barang = Barang::where('status', 'tersedia')->latest()->paginate(10);
            return view('dashboard.barang.index', compact('barang'));
        }

        $barang = Barang::latest()->paginate(10);
        return view('dashboard.barang.index', compact('barang'));
    }

    public function create()
    {
        $this->authorizePetugas();

        // ambil semua kategori buat dropdown
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('dashboard.barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $this->authorizePetugas();

        $validated = $request->validate([
            'nama_barang' => ['required','string','max:100'],
            'deskripsi'   => ['nullable','string'],
            // kalau kamu simpan nama kategori langsung:
            'kategori'    => ['nullable','string','max:50'],
            'lokasi'      => ['nullable','string','max:100'],
            'status'      => ['required', Rule::in(['tersedia','dipinjam','rusak'])],
            'gambar'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        Barang::create($validated);

        return redirect()->route('dashboard.barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $this->authorizePetugas();

        // kirim kategori juga ke form edit
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('dashboard.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $this->authorizePetugas();

        $validated = $request->validate([
            'nama_barang' => ['required','string','max:100'],
            'deskripsi'   => ['nullable','string'],
            'kategori'    => ['nullable','string','max:50'],
            'lokasi'      => ['nullable','string','max:100'],
            'status'      => ['required', Rule::in(['tersedia','dipinjam','rusak'])],
            'gambar'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        $barang->update($validated);

        return redirect()->route('dashboard.barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $this->authorizePetugas();

        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('dashboard.barang.index')->with('success', 'Barang berhasil dihapus');
    }

    protected function authorizePetugas()
    {
        if (!auth()->check() || auth()->user()->role !== 'petugas') {
            abort(403, 'Hanya petugas yang boleh mengelola barang.');
        }
    }
}
