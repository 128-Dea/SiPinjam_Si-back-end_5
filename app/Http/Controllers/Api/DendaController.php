<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index()
    {
        return response()->json(Denda::all());
    }

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

    public function show($id)
    {
        $denda = Denda::findOrFail($id);
        return response()->json($denda);
    }

    public function update(Request $request, $id)
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

    public function destroy($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->delete();

        return response()->json(['message' => 'Denda deleted successfully']);
    }
}
