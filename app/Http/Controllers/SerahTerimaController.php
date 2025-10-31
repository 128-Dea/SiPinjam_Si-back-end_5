<?php

namespace App\Http\Controllers;

use App\Models\SerahTerima;
use Illuminate\Http\Request;

class SerahTerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serahTerimas = SerahTerima::all();
        return response()->json($serahTerimas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'pengguna_id' => 'required|exists:pengguna,id',
            'tanggal_serah_terima' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $serahTerima = SerahTerima::create($request->all());
        return response()->json($serahTerima, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $serahTerima = SerahTerima::findOrFail($id);
        return response()->json($serahTerima);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $serahTerima = SerahTerima::findOrFail($id);

        $request->validate([
            'peminjaman_id' => 'sometimes|required|exists:peminjaman,id',
            'pengguna_id' => 'sometimes|required|exists:pengguna,id',
            'tanggal_serah_terima' => 'sometimes|required|date',
            'catatan' => 'nullable|string',
        ]);

        $serahTerima->update($request->all());
        return response()->json($serahTerima);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $serahTerima = SerahTerima::findOrFail($id);
        $serahTerima->delete();
        return response()->json(['message' => 'SerahTerima deleted successfully']);
    }
}
