<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Support\RiwayatLogger;


class PengembalianController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'mahasiswa') {
            $pengembalian = Pengembalian::with('peminjaman.barang')
                ->whereHas('peminjaman', function($q) {
                    $q->where('pengguna_id', auth()->id());
                })
                ->latest()
                ->paginate(10);
        } else {
            $pengembalian = Pengembalian::with('peminjaman.barang', 'peminjaman.pengguna')
                ->latest()
                ->paginate(10);
        }

        return view('dashboard.pengembalian.index', compact('pengembalian'));
    }

    public function create()
    {
        if (auth()->user()->role != 'mahasiswa') {
            return redirect()->route('dashboard.pengembalian.index')
                ->with('error', 'Petugas tidak boleh menambah pengembalian.');
        }

        $peminjaman = Peminjaman::where('pengguna_id', auth()->id())
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->get();

        return view('dashboard.pengembalian.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role != 'mahasiswa') {
            return redirect()->route('dashboard.pengembalian.index')
                ->with('error', 'Petugas tidak boleh menambah pengembalian.');
        }

        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'catatan'       => 'nullable|string',
        ]);

        $validated['tanggal_pengembalian'] = now();

        $pengembalian = Pengembalian::create($validated);

        // update peminjaman dan barang
        $peminjaman = Peminjaman::find($validated['peminjaman_id']);
        if ($peminjaman) {
            $peminjaman->update(['status' => 'dikembalikan']);
            $peminjaman->barang->update(['status' => 'tersedia']);
        }

        return redirect()->route('dashboard.pengembalian.index')
            ->with('success', 'Pengembalian berhasil disimpan.');
    }

    // petugas cuma lihat → edit/destroy kita kunci saja
    public function edit()  { abort(403); }
    public function update(){ abort(403); }
    public function destroy(){ abort(403); }
}
