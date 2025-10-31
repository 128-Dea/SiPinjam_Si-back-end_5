<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Qr;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function index()
    {
        $qrs = Qr::with('barang')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar QR Code berhasil diambil.',
            'data' => $qrs
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'kode_qr' => 'required|string|unique:qr,kode_qr',
            'data_qr' => 'required|string',
        ]);

        $qr = Qr::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil disimpan.',
            'data' => $qr
        ], 201);
    }

    public function show(Qr $qr)
    {
        $qr->load('barang');

        return response()->json([
            'success' => true,
            'message' => 'Detail QR Code berhasil diambil.',
            'data' => $qr
        ]);
    }

    public function update(Request $request, Qr $qr)
    {
        $validated = $request->validate([
            'barang_id' => 'sometimes|required|exists:barang,id',
            'kode_qr' => 'sometimes|required|string|unique:qr,kode_qr,' . $qr->id,
            'data_qr' => 'sometimes|required|string',
        ]);

        $qr->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil diperbarui.',
            'data' => $qr
        ]);
    }

    public function destroy(Qr $qr)
    {
        $qr->delete();

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil dihapus.'
        ]);
    }
}
