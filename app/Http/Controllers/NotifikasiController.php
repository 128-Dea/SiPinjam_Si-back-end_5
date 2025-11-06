<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'mahasiswa') {
            $notifikasis = Notifikasi::with(['pengguna','barang'])
                ->forMahasiswa($user->id)->latest()->paginate(10);
        } else {
            $notifikasis = Notifikasi::with(['pengguna','barang'])
                ->forPetugas()->latest()->paginate(10);
        }

        return view('notifikasi.index', compact('notifikasis'));
    }

    public function create()
    {
        $pengguna = Pengguna::all();
        return view('notifikasi.create', compact('pengguna'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengguna_id' => 'nullable|exists:pengguna,id',
            'barang_id'   => 'nullable|exists:barang,id',
            'judul'       => 'required|string|max:100',
            'pesan'       => 'required|string',
            'tipe'        => 'required|in:info,warning,error',
            'role_target' => 'required|in:mahasiswa,petugas',
            'dibaca'      => 'boolean',
        ]);

        if ($validated['role_target']==='mahasiswa' && empty($validated['pengguna_id'])) {
            return back()->withErrors(['pengguna_id'=>'Wajib diisi untuk target mahasiswa']);
        }

        Notifikasi::create($validated);
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dibuat');
    }

    public function show(Notifikasi $notifikasi)
    {
        return view('notifikasi.show', compact('notifikasi'));
    }

    public function edit(Notifikasi $notifikasi)
    {
        $pengguna = Pengguna::all();
        return view('notifikasi.edit', compact('notifikasi', 'pengguna'));
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
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil diperbarui');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dihapus');
    }
}
