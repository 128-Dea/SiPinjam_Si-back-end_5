<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return response()->json($services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal_service' => 'required|date',
            'deskripsi_service' => 'required|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,sedang_dikerjakan,selesai',
        ]);

        $service = Service::create($request->all());
        return response()->json($service, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'barang_id' => 'sometimes|required|exists:barang,id',
            'tanggal_service' => 'sometimes|required|date',
            'deskripsi_service' => 'sometimes|required|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:pending,sedang_dikerjakan,selesai',
        ]);

        $service->update($request->all());
        return response()->json($service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully']);
    }
}
