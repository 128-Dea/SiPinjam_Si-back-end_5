<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $riwayats = Riwayat::all();
        return response()->json($riwayats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'aksi' => 'required|string',
            'detail' => 'nullable|string',
        ]);

        $riwayat = Riwayat::create($request->all());
        return response()->json($riwayat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $riwayat = Riwayat::findOrFail($id);
        return response()->json($riwayat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $riwayat = Riwayat::findOrFail($id);

        $request->validate([
            'pengguna_id' => 'sometimes|required|exists:pengguna,id',
            'aksi' => 'sometimes|required|string',
            'detail' => 'nullable|string',
        ]);

        $riwayat->update($request->all());
        return response()->json($riwayat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $riwayat = Riwayat::findOrFail($id);
        $riwayat->delete();
        return response()->json(['message' => 'Riwayat deleted successfully']);
    }
}
