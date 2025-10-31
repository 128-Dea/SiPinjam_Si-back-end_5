<?php

namespace App\Http\Controllers;

use App\Models\Perpanjangan;
use Illuminate\Http\Request;

class PerpanjanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perpanjangans = Perpanjangan::all();
        return response()->json($perpanjangans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_perpanjangan' => 'required|date',
            'alasan' => 'required|string',
            'status' => 'required|in:pending,disetujui,ditolak',
        ]);

        $perpanjangan = Perpanjangan::create($request->all());
        return response()->json($perpanjangan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perpanjangan = Perpanjangan::findOrFail($id);
        return response()->json($perpanjangan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $perpanjangan = Perpanjangan::findOrFail($id);

        $request->validate([
            'peminjaman_id' => 'sometimes|required|exists:peminjaman,id',
            'tanggal_perpanjangan' => 'sometimes|required|date',
            'alasan' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,disetujui,ditolak',
        ]);

        $perpanjangan->update($request->all());
        return response()->json($perpanjangan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $perpanjangan = Perpanjangan::findOrFail($id);
        $perpanjangan->delete();
        return response()->json(['message' => 'Perpanjangan deleted successfully']);
    }
}
