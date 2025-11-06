<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\KeluhanLampiran;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Support\RiwayatLogger;


class KeluhanController extends Controller
{
    // ROLE:
    // - mahasiswa: bisa INDEX (punya sendiri), SHOW (punya sendiri), STORE (WAJIB bukti)
    // - petugas:   bisa INDEX (semua), SHOW (semua), TIDAK BOLEH STORE/UPDATE/DESTROY (read-only)

    public function index()
    {
        $query = Keluhan::with(['pengguna', 'barang', 'peminjaman', 'lampiran'])->latest();

        if (Auth::user()->role === 'mahasiswa') {
            $query->where('pengguna_id', Auth::id());
        }

        $keluhans = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar keluhan berhasil diambil.',
            'data'    => $keluhans,
        ]);
    }

    public function store(Request $request)
    {
        // Hanya mahasiswa yang boleh menambahkan
        if (Auth::user()->role !== 'mahasiswa') {
            return response()->json(['success' => false, 'message' => 'Hanya mahasiswa yang boleh menambahkan keluhan.'], 403);
        }

        $validated = $request->validate([
            'peminjaman_id'     => ['required', Rule::exists('peminjaman', 'id')],
            'deskripsi_keluhan' => 'required|string',
            'status'            => 'nullable|in:pending,diproses,selesai', // default pending
            'bukti'             => 'required|array|min:1',
            'bukti.*'           => 'required|file|mimes:jpg,jpeg,png,heic,mp4,mov,avi,mkv,webm|max:51200', // max 50MB/berkas
        ]);

        // pastikan peminjaman milik mahasiswa yang login
        $peminjaman = Peminjaman::with('barang')->findOrFail($validated['peminjaman_id']);
        if ($peminjaman->pengguna_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Peminjaman tidak dimiliki oleh pengguna ini.'], 403);
        }

        // tentukan barang_id dari peminjaman
        $barangId = $peminjaman->barang_id ?? null;

        $keluhan = Keluhan::create([
            'pengguna_id'       => Auth::id(),
            'barang_id'         => $barangId,
            'peminjaman_id'     => $validated['peminjaman_id'],
            'deskripsi_keluhan' => $validated['deskripsi_keluhan'],
            'status'            => $validated['status'] ?? 'pending',
        ]);

        // simpan bukti
        foreach ($request->file('bukti') as $file) {
            $stored = $file->store('keluhan', 'public'); // storage/app/public/keluhan/...
            KeluhanLampiran::create([
                'keluhan_id'    => $keluhan->id,
                'path'          => $stored,
                'mime'          => $file->getClientMimeType(),
                'size'          => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        $keluhan->load(['pengguna','barang','peminjaman','lampiran']);

        return response()->json([
            'success' => true,
            'message' => 'Keluhan berhasil dikirim.',
            'data'    => $keluhan,
        ], 201);
    }

    public function show(Keluhan $keluhan)
    {
        // mahasiswa hanya boleh lihat miliknya sendiri
        if (Auth::user()->role === 'mahasiswa' && $keluhan->pengguna_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak berwenang melihat keluhan ini.'], 403);
        }

        $keluhan->load(['pengguna', 'barang', 'peminjaman', 'lampiran']);

        return response()->json([
            'success' => true,
            'message' => 'Detail keluhan berhasil diambil.',
            'data'    => $keluhan,
        ]);
    }

    public function update(Request $request, Keluhan $keluhan)
    {
        // READ-ONLY untuk petugas sesuai permintaan → tolak semua update
        return response()->json(['success' => false, 'message' => 'Perubahan keluhan tidak diizinkan.'], 403);
    }

    public function destroy(Keluhan $keluhan)
    {
        // READ-ONLY untuk petugas, mahasiswa juga tidak diizinkan hapus
        return response()->json(['success' => false, 'message' => 'Penghapusan keluhan tidak diizinkan.'], 403);
    }
}
