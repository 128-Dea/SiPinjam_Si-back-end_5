<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use Illuminate\Http\Request;

class KeluhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keluhans = Keluhan::all();
        return response()->json($keluhans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'barang_id' => 'required|exists:barang,id',
            'deskripsi_keluhan' => 'required|string',
            'status' => 'required|in:pending,diproses,selesai',
        ]);

        $keluhan = Keluhan::create($request->all());
        return response()->json($keluhan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $keluhan = Keluhan::findOrFail($id);
        return response()->json($keluhan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $keluhan = Keluhan::findOrFail($id);

        $request->validate([
            'pengguna_id' => 'sometimes|required|exists:pengguna,id',
            'barang_id' => 'sometimes|required|exists:barang,id',
            'deskripsi_keluhan' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,diproses,selesai',
        ]);

        $keluhan->update($request->all());
        return response()->json($keluhan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $keluhan = Keluhan::findOrFail($id);
        $keluhan->delete();
        return response()->json(['message' => 'Keluhan deleted successfully']);
    }
}
