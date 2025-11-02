<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    // ========================
    // GET /api/barang
    // ========================
    public function index(Request $request)
    {
        $user = $request->user(); // karena kita pakai sanctum

        // kalau mahasiswa → hanya lihat barang
        if ($user && $user->role === 'mahasiswa') {
            $barang = Barang::where('status', 'tersedia')
                ->latest()
                ->paginate((int) $request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'message' => 'Daftar barang tersedia.',
                'data'    => $barang,
            ]);
        }

        // kalau petugas → semua
        $barang = Barang::latest()->paginate((int) $request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar barang berhasil diambil.',
            'data'    => $barang,
        ]);
    }

    // ========================
    // POST /api/barang
    // hanya petugas
    // ========================
    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang boleh menambahkan barang.',
            ], 403);
        }

        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:100'],
            'deskripsi'   => ['nullable', 'string'],
            'kategori'    => ['nullable', 'string', 'max:50'],
            'lokasi'      => ['nullable', 'string', 'max:100'],
            'status'      => ['required', Rule::in(['tersedia', 'dipinjam', 'rusak'])],
            'gambar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // handle upload gambar
        $path = null;
        if ($request->hasFile('gambar')) {
            // simpan di storage/app/public/barang
            $path = $request->file('gambar')->store('barang', 'public');
        }

        $barang = Barang::create([
            'nama_barang' => $validated['nama_barang'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'kategori'    => $validated['kategori'] ?? null,
            'lokasi'      => $validated['lokasi'] ?? null,
            'status'      => $validated['status'],
            'gambar'      => $path, // kolom ini nanti kita tambahkan di model/migrasi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan.',
            'data'    => $barang,
        ], 201);
    }

    // ========================
    // GET /api/barang/{id}
    // mahasiswa & petugas boleh
    // ========================
    public function show(Request $request, Barang $barang)
    {
        $user = $request->user();

        // kalau mahasiswa → boleh lihat detail, walau statusnya dipinjam
        // (kalau kamu mau batasi, tinggal cek di sini)
        return response()->json([
            'success' => true,
            'message' => 'Detail barang berhasil diambil.',
            'data'    => $barang,
        ]);
    }

    // ========================
    // PUT/PATCH /api/barang/{id}
    // hanya petugas
    // ========================
    public function update(Request $request, Barang $barang)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang boleh mengubah barang.',
            ], 403);
        }

        $validated = $request->validate([
            'nama_barang' => ['sometimes','required','string','max:100'],
            'deskripsi'   => ['nullable','string'],
            'kategori'    => ['nullable','string','max:50'],
            'lokasi'      => ['nullable','string','max:100'],
            'status'      => ['sometimes','required', Rule::in(['tersedia','dipinjam','rusak'])],
            'gambar'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        // kalau upload gambar baru → hapus lama
        if ($request->hasFile('gambar')) {
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $path = $request->file('gambar')->store('barang', 'public');
            $validated['gambar'] = $path;
        }

        $barang->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui.',
            'data'    => $barang,
        ]);
    }

    // ========================
    // DELETE /api/barang/{id}
    // hanya petugas
    // ========================
    public function destroy(Request $request, Barang $barang)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang boleh menghapus barang.',
            ], 403);
        }

        // hapus gambar kalau ada
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus.',
        ]);
    }
}
