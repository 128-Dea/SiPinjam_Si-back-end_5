<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use Illuminate\Http\Request;

class KeluhanController extends Controller
{
    public function index()
    {
        $keluhans = Keluhan::with(['pengguna', 'barang'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar keluhan berhasil diambil.',
            'data'    => $keluhans,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'barang_id' => 'required|exists:barang,id',
            'deskripsi_keluhan' => 'required|string',
            'status' => 'required|in:pending,diproses,selesai',
        ]);

        $keluhan = Keluhan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Keluhan berhasil dikirim.',
            'data'    => $keluhan,
        ], 201);
    }

    public function show(Keluhan $keluhan)
    {
        $keluhan->load(['pengguna', 'barang']);

        return response()->json([
            'success' => true,
            'message' => 'Detail keluhan berhasil diambil.',
            'data'    => $keluhan,
        ]);
    }

    public function update(Request $request, Keluhan $keluhan)
    {
        $validated = $request->validate([
            'deskripsi_keluhan' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,diproses,selesai',
        ]);

        $keluhan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Keluhan berhasil diperbarui.',
            'data'    => $keluhan,
        ]);
    }

    public function destroy(Keluhan $keluhan)
    {
        $keluhan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keluhan berhasil dihapus.',
        ]);
    }
}
