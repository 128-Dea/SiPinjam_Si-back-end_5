<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengembalians = Pengembalian::all();
        return response()->json($pengembalians);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_barang' => 'required|string',
            'denda' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $pengembalian = Pengembalian::create($request->all());
        return response()->json($pengembalian, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        return response()->json($pengembalian);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        $request->validate([
            'peminjaman_id' => 'sometimes|required|exists:peminjaman,id',
            'tanggal_pengembalian' => 'sometimes|required|date',
            'kondisi_barang' => 'sometimes|required|string',
            'denda' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $pengembalian->update($request->all());
        return response()->json($pengembalian);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->delete();
        return response()->json(['message' => 'Pengembalian deleted successfully']);
    }
}
