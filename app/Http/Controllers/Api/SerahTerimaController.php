<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SerahTerima;
use Illuminate\Http\Request;

class SerahTerimaController extends Controller
{
    public function index()
    {
        $serahTerimas = SerahTerima::with(['peminjaman', 'pengguna'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data serah terima berhasil diambil.',
            'data' => $serahTerimas,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'pengguna_id' => 'required|exists:pengguna,id',
            'tanggal_serah_terima' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $serahTerima = SerahTerima::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Serah terima berhasil ditambahkan.',
            'data' => $serahTerima,
        ], 201);
    }

    public function show(SerahTerima $serahTerima)
    {
        $serahTerima->load(['peminjaman', 'pengguna']);

        return response()->json([
            'success' => true,
            'message' => 'Detail serah terima berhasil diambil.',
            'data' => $serahTerima,
        ]);
    }

    public function update(Request $request, SerahTerima $serahTerima)
    {
        $validated = $request->validate([
            'peminjaman_id' => 'sometimes|required|exists:peminjaman,id',
            'pengguna_id' => 'sometimes|required|exists:pengguna,id',
            'tanggal_serah_terima' => 'sometimes|required|date',
            'catatan' => 'nullable|string',
        ]);

        $serahTerima->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Serah terima berhasil diperbarui.',
            'data' => $serahTerima,
        ]);
    }

    public function destroy(SerahTerima $serahTerima)
    {
        $serahTerima->delete();

        return response()->json([
            'success' => true,
            'message' => 'Serah terima berhasil dihapus.',
        ]);
    }
}
