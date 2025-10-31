<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::with('pengguna')->latest()->paginate(10);
        return view('notifikasi.index', compact('notifikasis'));
    }

    public function create()
    {
        $pengguna = Pengguna::all();
        return view('notifikasi.create', compact('pengguna'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'judul' => 'required|string|max:100',
            'pesan' => 'required|string',
            'tipe' => 'required|in:info,warning,error',
            'dibaca' => 'boolean',
        ]);

        Notifikasi::create($validated);

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dibuat');
    }

    public function show(Notifikasi $notifikasi)
    {
        return view('notifikasi.show', compact('notifikasi'));
    }

    public function edit(Notifikasi $notifikasi)
    {
        $pengguna = Pengguna::all();
        return view('notifikasi.edit', compact('notifikasi', 'pengguna'));
    }

    public function update(Request $request, Notifikasi $notifikasi)
    {
        $validated = $request->validate([
            'judul' => 'sometimes|required|string|max:100',
            'pesan' => 'sometimes|required|string',
            'tipe' => 'sometimes|required|in:info,warning,error',
            'dibaca' => 'boolean',
        ]);

        $notifikasi->update($validated);

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil diperbarui');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dihapus');
    }
}
