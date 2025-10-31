<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifikasis = Notifikasi::all();
        return response()->json($notifikasis);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'judul' => 'required|string',
            'pesan' => 'required|string',
            'tipe' => 'required|in:info,warning,error',
            'dibaca' => 'boolean',
        ]);

        $notifikasi = Notifikasi::create($request->all());
        return response()->json($notifikasi, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        return response()->json($notifikasi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $notifikasi = Notifikasi::findOrFail($id);

        $request->validate([
            'pengguna_id' => 'sometimes|required|exists:pengguna,id',
            'judul' => 'sometimes|required|string',
            'pesan' => 'sometimes|required|string',
            'tipe' => 'sometimes|required|in:info,warning,error',
            'dibaca' => 'boolean',
        ]);

        $notifikasi->update($request->all());
        return response()->json($notifikasi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->delete();
        return response()->json(['message' => 'Notifikasi deleted successfully']);
    }
}
