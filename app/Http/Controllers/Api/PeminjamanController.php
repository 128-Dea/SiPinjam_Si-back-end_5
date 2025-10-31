<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['pengguna', 'barang'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar peminjaman berhasil diambil.',
            'data'    => $peminjamans,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'barang_id' => 'required|exists:barang,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'catatan' => 'nullable|string',
        ]);

        $validated['status'] = 'dipinjam';

        $peminjaman = Peminjaman::create($validated);

        // update status barang
        Barang::where('id', $validated['barang_id'])->update(['status' => 'dipinjam']);

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil dibuat.',
            'data'    => $peminjaman,
        ], 201);
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['pengguna', 'barang']);

        return response()->json([
            'success' => true,
            'message' => 'Detail peminjaman berhasil diambil.',
            'data'    => $peminjaman,
        ]);
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'tanggal_kembali' => 'sometimes|required|date|after_or_equal:tanggal_pinjam',
            'tanggal_dikembalikan' => 'nullable|date',
            'status' => 'sometimes|required|in:pending,dipinjam,dikembalikan',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman->update($validated);

        if (isset($validated['status']) && $validated['status'] === 'dikembalikan') {
            Barang::where('id', $peminjaman->barang_id)->update(['status' => 'tersedia']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data peminjaman berhasil diperbarui.',
            'data'    => $peminjaman,
        ]);
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil dihapus.',
        ]);
    }
}
