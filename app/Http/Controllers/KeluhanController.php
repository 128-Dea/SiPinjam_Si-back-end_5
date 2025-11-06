<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\KeluhanLampiran;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Support\RiwayatLogger;

use Illuminate\Support\Facades\Auth;

class KeluhanController extends Controller
{
    public function index()
    {
        $query = Keluhan::with(['pengguna','barang','peminjaman','lampiran'])->latest();

        if (Auth::user()->role === 'mahasiswa') {
            $query->where('pengguna_id', Auth::id());
        }

        $keluhans = $query->paginate(10);
        return view('dashboard.keluhan.index', compact('keluhans'));
    }

    public function create()
    {
        // hanya mahasiswa yang boleh membuat
        if (Auth::user()->role !== 'mahasiswa') {
            abort(403, 'Hanya mahasiswa yang dapat menambahkan keluhan.');
        }

        // hanya list peminjaman milik mahasiswa
        $peminjaman = Peminjaman::where('pengguna_id', Auth::id())->latest()->get();
        return view('dashboard.keluhan.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            abort(403, 'Hanya mahasiswa yang dapat menambahkan keluhan.');
        }

        $validated = $request->validate([
            'peminjaman_id'     => 'required|exists:peminjaman,id',
            'deskripsi_keluhan' => 'required|string',
            'bukti'             => 'required|array|min:1',
            'bukti.*'           => 'required|file|mimes:jpg,jpeg,png,heic,mp4,mov,avi,mkv,webm|max:51200',
        ]);

        $peminjaman = Peminjaman::with('barang')->findOrFail($validated['peminjaman_id']);
        if ($peminjaman->pengguna_id !== Auth::id()) {
            abort(403, 'Peminjaman tidak dimiliki oleh akun ini.');
        }

        $keluhan = Keluhan::create([
            'pengguna_id'       => Auth::id(),
            'barang_id'         => $peminjaman->barang_id ?? null,
            'peminjaman_id'     => $validated['peminjaman_id'],
            'deskripsi_keluhan' => $validated['deskripsi_keluhan'],
            'status'            => 'pending',
        ]);

        foreach ($request->file('bukti') as $file) {
            $stored = $file->store('keluhan', 'public');
            KeluhanLampiran::create([
                'keluhan_id'    => $keluhan->id,
                'path'          => $stored,
                'mime'          => $file->getClientMimeType(),
                'size'          => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('keluhan.index')->with('success', 'Keluhan berhasil ditambahkan');
    }

    // READ-ONLY untuk semua role → tidak ada edit/update/destroy
    public function edit(Keluhan $keluhan)  { abort(403); }
    public function update(Request $r, Keluhan $k) { abort(403); }
    public function destroy(Keluhan $keluhan) { abort(403); }
}
