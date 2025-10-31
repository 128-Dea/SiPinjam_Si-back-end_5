<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Riwayat;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayats = Riwayat::with('pengguna')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar riwayat berhasil diambil.',
            'data' => $riwayats
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'aksi' => 'required|string',
            'detail' => 'nullable|string',
        ]);

        $riwayat = Riwayat::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat berhasil disimpan.',
            'data' => $riwayat
        ], 201);
    }

    public function show(Riwayat $riwayat)
    {
        $riwayat->load('pengguna');

        return response()->json([
            'success' => true,
            'message' => 'Detail riwayat berhasil diambil.',
            'data' => $riwayat
        ]);
    }

    public function update(Request $request, Riwayat $riwayat)
    {
        $validated = $request->validate([
            'pengguna_id' => 'sometimes|required|exists:pengguna,id',
            'aksi' => 'sometimes|required|string',
            'detail' => 'nullable|string',
        ]);

        $riwayat->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat berhasil diperbarui.',
            'data' => $riwayat
        ]);
    }

    public function destroy(Riwayat $riwayat)
    {
        $riwayat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat berhasil dihapus.'
        ]);
    }
}
