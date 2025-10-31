<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use App\Models\Barang;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function index()
    {
        $qrs = Qr::with('barang')->latest()->paginate(10);
        return view('qr.index', compact('qrs'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('qr.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'kode_qr' => 'required|string|unique:qr,kode_qr',
            'data_qr' => 'required|string',
        ]);

        Qr::create($validated);
        return redirect()->route('qr.index')->with('success', 'QR Code berhasil ditambahkan.');
    }

    public function show(Qr $qr)
    {
        $qr->load('barang');
        return view('qr.show', compact('qr'));
    }

    public function edit(Qr $qr)
    {
        $barangs = Barang::all();
        return view('qr.edit', compact('qr', 'barangs'));
    }

    public function update(Request $request, Qr $qr)
    {
        $validated = $request->validate([
            'barang_id' => 'sometimes|required|exists:barang,id',
            'kode_qr' => 'sometimes|required|string|unique:qr,kode_qr,' . $qr->id,
            'data_qr' => 'sometimes|required|string',
        ]);

        $qr->update($validated);
        return redirect()->route('qr.index')->with('success', 'QR Code berhasil diperbarui.');
    }

    public function destroy(Qr $qr)
    {
        $qr->delete();
        return redirect()->route('qr.index')->with('success', 'QR Code berhasil dihapus.');
    }
}
