<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::latest()->paginate(10);
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => ['required','string','max:100'],
            'deskripsi'   => ['nullable','string'],
            'kategori'    => ['nullable','string','max:50'],
            'lokasi'      => ['nullable','string','max:100'],
            'status'      => ['required', Rule::in(['tersedia','dipinjam','rusak'])],
        ]);

        Barang::create($validated);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => ['required','string','max:100'],
            'deskripsi'   => ['nullable','string'],
            'kategori'    => ['nullable','string','max:50'],
            'lokasi'      => ['nullable','string','max:100'],
            'status'      => ['required', Rule::in(['tersedia','dipinjam','rusak'])],
        ]);

        $barang->update($validated);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
