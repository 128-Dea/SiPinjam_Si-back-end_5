<?php

namespace App\Http\Controllers;

use App\Models\Perpanjangan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PerpanjanganController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'mahasiswa') {
            $perpanjangan = Perpanjangan::with('peminjaman.barang')
                ->whereHas('peminjaman', function($q){
                    $q->where('pengguna_id', auth()->id());
                })
                ->latest()
                ->paginate(10);
        } else {
            $perpanjangan = Perpanjangan::with('peminjaman.barang', 'peminjaman.pengguna')
                ->latest()
                ->paginate(10);
        }

        return view('dashboard.perpanjangan.index', compact('perpanjangan'));
    }

    public function create()
    {
        if (auth()->user()->role != 'mahasiswa') {
            return redirect()->route('dashboard.perpanjangan.index')
                ->with('error', 'Petugas tidak dapat menambah perpanjangan.');
        }

        $peminjaman = Peminjaman::where('pengguna_id', auth()->id())
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->get();

        return view('dashboard.perpanjangan.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role != 'mahasiswa') {
            return redirect()->route('dashboard.perpanjangan.index')
                ->with('error', 'Petugas tidak dapat menambah perpanjangan.');
        }

        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'alasan'        => 'required|string|max:255',
        ]);

        $validated['tanggal_perpanjangan'] = now();
        $validated['status'] = 'pending';

        Perpanjangan::create($validated);

        return redirect()->route('dashboard.perpanjangan.index')
            ->with('success', 'Perpanjangan berhasil diajukan.');
    }

    // PETUGAS menyetujui / menolak
    public function edit(Perpanjangan $perpanjangan)
    {
        if (auth()->user()->role != 'petugas') {
            return redirect()->route('dashboard.perpanjangan.index')
                ->with('error', 'Hanya petugas yang bisa menyetujui atau menolak.');
        }

        return view('dashboard.perpanjangan.edit', compact('perpanjangan'));
    }

    public function update(Request $request, Perpanjangan $perpanjangan)
    {
        if (auth()->user()->role != 'petugas') {
            return redirect()->route('dashboard.perpanjangan.index')
                ->with('error', 'Hanya petugas yang bisa mengubah status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $perpanjangan->update($validated);

        return redirect()->route('dashboard.perpanjangan.index')
            ->with('success', 'Status perpanjangan diperbarui.');
    }
}
