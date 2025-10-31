<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barang = Barang::latest()->paginate((int) $request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar barang berhasil diambil.',
            'data'    => $barang,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:100'],
            'deskripsi'   => ['nullable', 'string'],
            'kategori'    => ['nullable', 'string', 'max:50'],
            'lokasi'      => ['nullable', 'string', 'max:100'],
            'status'      => ['required', Rule::in(['tersedia', 'dipinjam', 'rusak'])],
        ]);

        $barang = Barang::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan.',
            'data'    => $barang,
        ], 201);
    }

    public function show(Barang $barang)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail barang berhasil diambil.',
            'data'    => $barang,
        ]);
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => ['sometimes','required','string','max:100'],
            'deskripsi'   => ['nullable','string'],
            'kategori'    => ['nullable','string','max:50'],
            'lokasi'      => ['nullable','string','max:100'],
            'status'      => ['sometimes','required', Rule::in(['tersedia','dipinjam','rusak'])],
        ]);

        $barang->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui.',
            'data'    => $barang,
        ]);
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus.',
        ]);
    }
}
