<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengguna;
use App\Models\Barang;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'mahasiswa') {
            $pengguna = auth()->user();
            $barang = Barang::where('status', 'tersedia')->get();
            return view('dashboard.peminjaman.create', compact('pengguna', 'barang'));
        } else {
            $peminjamans = Peminjaman::with(['pengguna', 'barang'])->latest()->paginate(10);
            return view('dashboard.peminjaman.index', compact('peminjamans'));
        }
    }

    public function create()
    {
        $pengguna = Pengguna::all();
        $barang = Barang::where('status', 'tersedia')->get();
        return view('peminjaman.create', compact('pengguna', 'barang'));
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

        if (auth()->user()->role == 'mahasiswa') {
            $validated['status'] = 'pending';
        } else {
            $validated['status'] = 'dipinjam';
            // update status barang jadi 'dipinjam'
            Barang::where('id', $validated['barang_id'])->update(['status' => 'dipinjam']);
        }

        Peminjaman::create($validated);

        return redirect()->route('dashboard.peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan');
    }

    public function edit(Peminjaman $peminjaman)
    {
        $pengguna = Pengguna::all();
        $barang = Barang::all();
        return view('peminjaman.edit', compact('peminjaman', 'pengguna', 'barang'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'tanggal_kembali' => 'sometimes|required|date|after_or_equal:tanggal_pinjam',
            'tanggal_dikembalikan' => 'nullable|date',
            'status' => 'required|in:pending,dipinjam,dikembalikan',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman->update($validated);

        if ($validated['status'] === 'dikembalikan') {
            Barang::where('id', $peminjaman->barang_id)->update(['status' => 'tersedia']);
        } elseif ($validated['status'] === 'dipinjam') {
            Barang::where('id', $peminjaman->barang_id)->update(['status' => 'dipinjam']);
        }

        return redirect()->route('dashboard.peminjaman.index')->with('success', 'Data peminjaman diperbarui');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('dashboard.peminjaman.index')->with('success', 'Peminjaman berhasil dihapus');
    }
}
