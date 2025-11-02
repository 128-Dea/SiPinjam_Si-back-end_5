<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    // PETUGAS: lihat semua, mahasiswa: lihat miliknya
    public function index()
    {
        if (Auth::user()->role === 'mahasiswa') {
            $pengembalian = Pengembalian::with(['peminjaman.barang'])
                ->whereHas('peminjaman', function ($q) {
                    $q->where('pengguna_id', Auth::id());
                })
                ->latest()
                ->get();
        } else {
            $pengembalian = Pengembalian::with(['peminjaman.barang', 'peminjaman.pengguna'])
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengembalian berhasil diambil.',
            'data'    => $pengembalian,
        ]);
    }

    // MAHASISWA: tambah pengembalian
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            return response()->json([
                'success' => false,
                'message' => 'Petugas tidak diperbolehkan menambah pengembalian.',
            ], 403);
        }

        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'catatan'       => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::where('id', $validated['peminjaman_id'])
            ->where('pengguna_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak valid atau sudah dikembalikan.',
            ], 403);
        }

        $validated['tanggal_pengembalian'] = now();

        $pengembalian = Pengembalian::create($validated);

        // update peminjaman & barang
        $peminjaman->update(['status' => 'dikembalikan']);
        Barang::where('id', $peminjaman->barang_id)->update(['status' => 'tersedia']);

        return response()->json([
            'success' => true,
            'message' => 'Pengembalian berhasil disimpan.',
            'data'    => $pengembalian->load('peminjaman.barang'),
        ], 201);
    }

    public function show(Pengembalian $pengembalian)
    {
        $pengembalian->load('peminjaman.barang', 'peminjaman.pengguna');

        // batasi milik sendiri kalau mahasiswa
        if (Auth::user()->role === 'mahasiswa'
            && $pengembalian->peminjaman->pengguna_id !== Auth::id()) {

            return response()->json([
                'success' => false,
                'message' => 'Anda tidak boleh melihat data ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pengembalian berhasil diambil.',
            'data'    => $pengembalian,
        ]);
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        if (Auth::user()->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin.',
            ], 403);
        }

        $validated = $request->validate([
            'catatan' => 'nullable|string',
        ]);

        $pengembalian->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data pengembalian berhasil diperbarui.',
            'data'    => $pengembalian,
        ]);
    }

    public function destroy(Pengembalian $pengembalian)
    {
        if (Auth::user()->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin.',
            ], 403);
        }

        $pengembalian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pengembalian berhasil dihapus.',
        ]);
    }
}
