<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return response()->json($kategori);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|unique:kategori,nama_kategori',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return response()->json($kategori, 201);
    }

    public function show(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        return response()->json($kategori);
    }

    public function update(Request $request, string $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'sometimes|required|string|unique:kategori,nama_kategori,' . $id,
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return response()->json($kategori);
    }

    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return response()->json(['message' => 'Kategori deleted successfully']);
    }
}
