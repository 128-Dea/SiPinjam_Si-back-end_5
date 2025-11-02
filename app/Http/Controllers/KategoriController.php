<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        // Cek role: hanya petugas/admin yang boleh akses seluruh fitur kategori
        if (auth()->user()->role == 'mahasiswa') {
            return redirect()->route('dashboard.index')
                ->with('error', 'Anda tidak memiliki akses ke menu kategori.');
        }

        $kategori = Kategori::latest()->paginate(10);
        return view('dashboard.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('dashboard.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|unique:kategori,nama_kategori|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        Kategori::create($validated);
        return redirect()->route('dashboard.kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Kategori $kategori)
    {
        return view('dashboard.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|unique:kategori,nama_kategori,' . $kategori->id . '|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $kategori->update($validated);
        return redirect()->route('dashboard.kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('dashboard.kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
