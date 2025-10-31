<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Barang;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('barang')->latest()->paginate(10);
        return view('service.index', compact('services'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('service.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal_service' => 'required|date',
            'deskripsi_service' => 'required|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,sedang_dikerjakan,selesai',
        ]);

        Service::create($request->all());
        return redirect()->route('service.index')->with('success', 'Data service berhasil ditambahkan.');
    }

    public function show(Service $service)
    {
        $service->load('barang');
        return view('service.show', compact('service'));
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('service.index')->with('success', 'Data service berhasil dihapus.');
    }
}
