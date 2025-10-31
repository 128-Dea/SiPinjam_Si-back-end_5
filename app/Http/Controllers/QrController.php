<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use Illuminate\Http\Request;

class QrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qrs = Qr::all();
        return response()->json($qrs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'kode_qr' => 'required|string|unique:qr,kode_qr',
            'data_qr' => 'required|string',
        ]);

        $qr = Qr::create($request->all());
        return response()->json($qr, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $qr = Qr::findOrFail($id);
        return response()->json($qr);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $qr = Qr::findOrFail($id);

        $request->validate([
            'barang_id' => 'sometimes|required|exists:barang,id',
            'kode_qr' => 'sometimes|required|string|unique:qr,kode_qr,' . $id,
            'data_qr' => 'sometimes|required|string',
        ]);

        $qr->update($request->all());
        return response()->json($qr);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $qr = Qr::findOrFail($id);
        $qr->delete();
        return response()->json(['message' => 'Qr deleted successfully']);
    }
}
