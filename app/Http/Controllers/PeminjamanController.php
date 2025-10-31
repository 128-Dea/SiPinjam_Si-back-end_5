<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peminjamans = Peminjaman::all();
        return response()->json($peminjamans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'barang_id' => 'required|exists:barang,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'tanggal_dikembalikan' => 'nullable|date',
            'status' => 'required|in:pending,dipinjam,dikembalikan',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::create($request->all());
        return response()->json($peminjaman, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return response()->json($peminjaman);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $request->validate([
            'pengguna_id' => 'sometimes|required|exists:pengguna,id',
            'barang_id' => 'sometimes|required|exists:barang,id',
            'tanggal_pinjam' => 'sometimes|required|date',
            'tanggal_kembali' => 'sometimes|required|date|after:tanggal_pinjam',
            'tanggal_dikembalikan' => 'nullable|date',
            'status' => 'sometimes|required|in:pending,dipinjam,dikembalikan',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman->update($request->all());
        return response()->json($peminjaman);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();
        return response()->json(['message' => 'Peminjaman deleted successfully']);
    }
}
