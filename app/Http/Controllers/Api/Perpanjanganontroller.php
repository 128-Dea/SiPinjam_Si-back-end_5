<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perpanjangan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerpanjanganController extends Controller
{

    // ========= GET: Semua perpanjangan ==========

    public function index()
    {
        if (Auth::user()->role === 'mahasiswa') {
            // mahasiswa: hanya lihat perpanjangan miliknya
            $perpanjangan = Perpanjangan::with('peminjaman.barang')
                ->whereHas('peminjaman', function ($q) {
                    $q->where('pengguna_id', Auth::id());
                })
                ->latest()
                ->get();
        } else {
            // petugas: lihat semua
            $perpanjangan = Perpanjangan::with('peminjaman.barang', 'peminjaman.pengguna')
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar perpanjangan berhasil diambil.',
            'data'    => $perpanjangan,
        ]);
    }

  
    // ====== POST: Tambah perpanjangan (Mahasiswa) ==========

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            return response()->json([
                'success' => false,
                'message' => 'Petugas tidak diperbolehkan menambah perpanjangan.',
            ], 403);
        }

        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'alasan'        => 'required|string|max:255',
        ]);

        // Pastikan peminjaman milik user dan masih bisa diperpanjang
        $peminjaman = Peminjaman::where('id', $validated['peminjaman_id'])
            ->where('pengguna_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan atau tidak valid.',
            ], 403);
        }

        $validated['tanggal_perpanjangan'] = now();
        $validated['status'] = 'pending';

        $perpanjangan = Perpanjangan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Perpanjangan berhasil diajukan. Menunggu persetujuan petugas.',
            'data'    => $perpanjangan->load('peminjaman.barang'),
        ], 201);
    }

    // ======= GET: Detail perpanjangan ========

    public function show(Perpanjangan $perpanjangan)
    {
        $perpanjangan->load('peminjaman.barang', 'peminjaman.pengguna');

        // mahasiswa hanya boleh lihat miliknya
        if (Auth::user()->role === 'mahasiswa'
            && $perpanjangan->peminjaman->pengguna_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail perpanjangan berhasil diambil.',
            'data'    => $perpanjangan,
        ]);
    }


    // ======  PUT: Update status (Petugas)

    public function update(Request $request, Perpanjangan $perpanjangan)
    {
        if (Auth::user()->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang bisa mengubah status perpanjangan.',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $perpanjangan->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status perpanjangan berhasil diperbarui.',
            'data'    => $perpanjangan,
        ]);
    }

  
    // ========= DELETE: Hapus (Petugas) =======

    public function destroy(Perpanjangan $perpanjangan)
    {
        if (Auth::user()->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang bisa menghapus perpanjangan.',
            ], 403);
        }

        $perpanjangan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data perpanjangan berhasil dihapus.',
        ]);
    }
}
