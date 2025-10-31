<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perpanjangan;
use Illuminate\Http\Request;

class PerpanjanganController extends Controller
{
    public function index()
    {
        $perpanjangans = Perpanjangan::with('peminjaman')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar perpanjangan berhasil diambil.',
            'data' => $perpanjangans
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_perpanjangan' => 'required|date',
            'alasan' => 'required|string',
            'status' => 'required|in:pending,disetujui,ditolak',
        ]);

        $perpanjangan = Perpanjangan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Perpanjangan berhasil disimpan.',
            'data' => $perpanjangan
        ], 201);
    }

    public function show(Perpanjangan $perpanjangan)
    {
        $perpanjangan->load('peminjaman');

        return response()->json([
            'success' => true,
            'message' => 'Detail perpanjangan berhasil diambil.',
            'data' => $perpanjangan
        ]);
    }

    public function update(Request $request, Perpanjangan $perpanjangan)
    {
        $validated = $request->validate([
            'peminjaman_id' => 'sometimes|required|exists:peminjaman,id',
            'tanggal_perpanjangan' => 'sometimes|required|date',
            'alasan' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,disetujui,ditolak',
        ]);

        $perpanjangan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Perpanjangan berhasil diperbarui.',
            'data' => $perpanjangan
        ]);
    }

    public function destroy(Perpanjangan $perpanjangan)
    {
        $perpanjangan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perpanjangan berhasil dihapus.'
        ]);
    }
}
