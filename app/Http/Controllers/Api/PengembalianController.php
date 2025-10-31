<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Pengembalian::with('peminjaman')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengembalian berhasil diambil.',
            'data' => $pengembalians
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_barang' => 'required|string',
            'denda' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $pengembalian = Pengembalian::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data pengembalian berhasil disimpan.',
            'data' => $pengembalian
        ], 201);
    }

    public function show(Pengembalian $pengembalian)
    {
        $pengembalian->load('peminjaman');

        return response()->json([
            'success' => true,
            'message' => 'Detail pengembalian berhasil diambil.',
            'data' => $pengembalian
        ]);
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        $validated = $request->validate([
            'peminjaman_id' => 'sometimes|required|exists:peminjaman,id',
            'tanggal_pengembalian' => 'sometimes|required|date',
            'kondisi_barang' => 'sometimes|required|string',
            'denda' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $pengembalian->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data pengembalian berhasil diperbarui.',
            'data' => $pengembalian
        ]);
    }

    public function destroy(Pengembalian $pengembalian)
    {
        $pengembalian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pengembalian berhasil dihapus.'
        ]);
    }
}
