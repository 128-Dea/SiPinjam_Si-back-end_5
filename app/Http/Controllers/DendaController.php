<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dendas = Denda::all();
        return response()->json($dendas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'jumlah_denda' => 'required|numeric|min:0',
            'alasan' => 'required|string',
            'status' => 'required|in:belum_dibayar,dibayar',
        ]);

        $denda = Denda::create($request->all());
        return response()->json($denda, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $denda = Denda::findOrFail($id);
        return response()->json($denda);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $denda = Denda::findOrFail($id);

        $request->validate([
            'peminjaman_id' => 'sometimes|required|exists:peminjaman,id',
            'jumlah_denda' => 'sometimes|required|numeric|min:0',
            'alasan' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:belum_dibayar,dibayar',
        ]);

        $denda->update($request->all());
        return response()->json($denda);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $denda = Denda::findOrFail($id);
        $denda->delete();
        return response()->json(['message' => 'Denda deleted successfully']);
    }
}
