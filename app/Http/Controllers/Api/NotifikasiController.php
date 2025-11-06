<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $q = Notifikasi::with(['pengguna','barang']);

        if ($user->role === 'mahasiswa') {
            $q->forMahasiswa($user->id);
        } else {
            $q->forPetugas();
        }

        if ($request->filled('unread')) $q->unread();

        return response()->json([
            'success' => true,
            'message' => 'Daftar notifikasi berhasil diambil.',
            'data'    => $q->latest()->paginate(15),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengguna_id' => 'nullable|exists:pengguna,id', // wajib jika role_target=mahasiswa
            'barang_id'   => 'nullable|exists:barang,id',
            'judul'       => 'required|string|max:100',
            'pesan'       => 'required|string',
            'tipe'        => 'required|in:info,warning,error',
            'role_target' => 'required|in:mahasiswa,petugas',
            'dibaca'      => 'boolean',
        ]);

        if (($validated['role_target'] ?? null) === 'mahasiswa' && empty($validated['pengguna_id'])) {
            return response()->json(['success'=>false,'message'=>'pengguna_id wajib untuk target mahasiswa'], 422);
        }

        $notifikasi = Notifikasi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dibuat.',
            'data'    => $notifikasi,
        ], 201);
    }

    public function show(Notifikasi $notifikasi)
    {
        $notifikasi->load(['pengguna','barang']);
        return response()->json([
            'success' => true,
            'message' => 'Detail notifikasi berhasil diambil.',
            'data'    => $notifikasi,
        ]);
    }

    public function read(Notifikasi $notifikasi)
    {
        $notifikasi->update(['dibaca' => true]);
        return response()->json(['success'=>true,'message'=>'Notifikasi ditandai dibaca','data'=>$notifikasi]);
    }

    public function readAll()
    {
        $user = Auth::user();
        if ($user->role === 'mahasiswa') {
            Notifikasi::forMahasiswa($user->id)->update(['dibaca'=>true]);
        } else {
            Notifikasi::forPetugas()->update(['dibaca'=>true]);
        }
        return response()->json(['success'=>true,'message'=>'Semua notifikasi ditandai dibaca']);
    }

    public function update(Request $request, Notifikasi $notifikasi)
    {
        $validated = $request->validate([
            'judul'       => 'sometimes|required|string|max:100',
            'pesan'       => 'sometimes|required|string',
            'tipe'        => 'sometimes|required|in:info,warning,error',
            'role_target' => 'sometimes|required|in:mahasiswa,petugas',
            'dibaca'      => 'boolean',
        ]);

        $notifikasi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diperbarui.',
            'data'    => $notifikasi,
        ]);
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();
        return response()->json(['success'=>true,'message'=>'Notifikasi berhasil dihapus']);
    }
}
