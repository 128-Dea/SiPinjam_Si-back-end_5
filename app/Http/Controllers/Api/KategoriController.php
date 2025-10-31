<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil diambil.',
            'data'    => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|unique:kategori,nama_kategori|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $kategori = Kategori::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data'    => $kategori,
        ], 201);
    }

    public function show(Kategori $kategori)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail kategori berhasil diambil.',
            'data'    => $kategori,
        ]);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'sometimes|required|string|unique:kategori,nama_kategori,' . $kategori->id . '|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $kategori->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data'    => $kategori,
        ]);
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
