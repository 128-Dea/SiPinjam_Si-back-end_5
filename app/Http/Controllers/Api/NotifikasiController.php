<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::with('pengguna')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar notifikasi berhasil diambil.',
            'data'    => $notifikasis,
        ]);
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

        $notifikasi = Notifikasi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dibuat.',
            'data'    => $notifikasi,
        ], 201);
    }

    public function show(Notifikasi $notifikasi)
    {
        $notifikasi->load('pengguna');

        return response()->json([
            'success' => true,
            'message' => 'Detail notifikasi berhasil diambil.',
            'data'    => $notifikasi,
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diperbarui.',
            'data'    => $notifikasi,
        ]);
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus.',
        ]);
    }
}
