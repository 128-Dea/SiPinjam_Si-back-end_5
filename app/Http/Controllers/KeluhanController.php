<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\Pengguna;
use App\Models\Barang;
use Illuminate\Http\Request;

class KeluhanController extends Controller
{
    public function index()
    {
        $keluhans = Keluhan::with(['pengguna', 'barang'])->latest()->paginate(10);
        return view('keluhan.index', compact('keluhans'));
    }

    public function create()
    {
        $pengguna = Pengguna::all();
        $barang = Barang::all();
        return view('keluhan.create', compact('pengguna', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'barang_id' => 'required|exists:barang,id',
            'deskripsi_keluhan' => 'required|string',
            'status' => 'required|in:pending,diproses,selesai',
        ]);

        Keluhan::create($validated);

        return redirect()->route('keluhan.index')->with('success', 'Keluhan berhasil ditambahkan');
    }

    public function edit(Keluhan $keluhan)
    {
        $pengguna = Pengguna::all();
        $barang = Barang::all();
        return view('keluhan.edit', compact('keluhan', 'pengguna', 'barang'));
    }

    public function update(Request $request, Keluhan $keluhan)
    {
        $validated = $request->validate([
            'deskripsi_keluhan' => 'sometimes|required|string',
            'status' => 'required|in:pending,diproses,selesai',
        ]);

        $keluhan->update($validated);
        return redirect()->route('keluhan.index')->with('success', 'Keluhan berhasil diperbarui');
    }

    public function destroy(Keluhan $keluhan)
    {
        $keluhan->delete();
        return redirect()->route('keluhan.index')->with('success', 'Keluhan berhasil dihapus');
    }
}
