<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    /**
     * GET /barang
     * Tampilkan daftar barang (paginate biar aman).
     */
    public function index(Request $request)
    {
        // Bisa ganti ->paginate(15) ke ->get() kalau mau semua
        $barang = Barang::latest()->paginate(
            (int) $request->get('per_page', 15)
        );

        return response()->json($barang);
    }

    /**
     * POST /barang
     * Simpan barang baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => ['required','string','max:100'],
            'deskripsi'   => ['nullable','string'],
            'kategori'    => ['nullable','string','max:50'],
            'lokasi'      => ['nullable','string','max:100'],
            'status'      => ['required', Rule::in(['tersedia','dipinjam','rusak'])],
        ]);

        $barang = Barang::create($validated);

        return response()->json($barang, 201);
    }

    /**
     * GET /barang/{barang}
     * Detail barang.
     */
    public function show(Barang $barang)
    {
        return response()->json($barang);
    }

    /**
     * PUT/PATCH /barang/{barang}
     * Update data barang.
     */
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

        return response()->json($barang);
    }

    /**
     * DELETE /barang/{barang}
     * Hapus barang.
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();

        return response()->json([
            'message' => 'Barang deleted successfully'
        ]);
    }
}
