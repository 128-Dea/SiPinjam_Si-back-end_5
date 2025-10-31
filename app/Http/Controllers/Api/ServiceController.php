<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('barang')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data service berhasil diambil.',
            'data' => $services,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal_service' => 'required|date',
            'deskripsi_service' => 'required|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,sedang_dikerjakan,selesai',
        ]);

        $service = Service::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service berhasil ditambahkan.',
            'data' => $service,
        ], 201);
    }

    public function show(Service $service)
    {
        $service->load('barang');
        return response()->json([
            'success' => true,
            'message' => 'Detail service berhasil diambil.',
            'data' => $service,
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'barang_id' => 'sometimes|required|exists:barang,id',
            'tanggal_service' => 'sometimes|required|date',
            'deskripsi_service' => 'sometimes|required|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:pending,sedang_dikerjakan,selesai',
        ]);

        $service->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service berhasil diperbarui.',
            'data' => $service,
        ]);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service berhasil dihapus.',
        ]);
    }
}
