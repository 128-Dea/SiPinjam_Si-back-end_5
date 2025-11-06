<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Support\RiwayatLogger;


class DendaController extends Controller
{
    // Tampilkan daftar denda (untuk petugas)
    public function index()
    {
        $dendas = Denda::with(['peminjaman'])->latest()->paginate(10);
        return view('denda.index', compact('dendas'));
    }

    // Form tambah
    public function create()
    {
        $peminjamans = Peminjaman::with('barang','pengguna')
            ->latest()->limit(100)->get(); // atau bikin pencarian AJAX
        return view('denda.create', compact('peminjamans'));
    }

    // Simpan denda (hitungan otomatis jika terlambat)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id'    => 'required|exists:peminjaman,id',
            'jenis_denda'      => 'required|in:terlambat,hilang',
            'total_denda'      => 'nullable|numeric|min:0', // wajib bila hilang
            'status_pembayaran'=> 'required|in:belum_dibayar,dibayar',
            'keterangan'       => 'nullable|string',
        ]);

        $p = Peminjaman::with('barang','pengguna')->findOrFail($validated['peminjaman_id']);

        // Hitung otomatis jika terlambat
        if ($validated['jenis_denda'] === 'terlambat') {
            $due = Carbon::parse($p->due_at);
            $end = $p->returned_at ? Carbon::parse($p->returned_at) : now();
            $menitTelat = $end->greaterThan($due) ? $end->diffInMinutes($due) : 0;

            if ($menitTelat <= 0) {
                return back()->withErrors(['peminjaman_id'=>'Tidak ada keterlambatan pada peminjaman ini.'])->withInput();
            }

            $validated['total_denda'] = $menitTelat * 1000; // Rp1.000/menit
            $validated['keterangan'] = $validated['keterangan'] ?? "Keterlambatan {$menitTelat} menit x Rp1.000";
        } else {
            // hilang -> total wajib diisi
            if (!isset($validated['total_denda']) || $validated['total_denda'] <= 0) {
                return back()->withErrors(['total_denda'=>'Total denda wajib diisi untuk denda hilang.'])->withInput();
            }
        }

        Denda::create($validated);

        return redirect()->route('denda.index')->with('success','Denda berhasil dibuat.');
    }

    // Detail (opsional)
    public function show(Denda $denda)
    {
        $denda->load('peminjaman');
        return view('denda.show', compact('denda'));
    }

    // Form edit
    public function edit(Denda $denda)
    {
        $peminjamans = Peminjaman::with('barang','pengguna')->latest()->limit(100)->get();
        return view('denda.edit', compact('denda','peminjamans'));
    }

    // Update
    public function update(Request $request, Denda $denda)
    {
        $validated = $request->validate([
            'peminjaman_id'    => 'required|exists:peminjaman,id',
            'jenis_denda'      => 'required|in:terlambat,hilang',
            'total_denda'      => 'nullable|numeric|min:0',
            'status_pembayaran'=> 'required|in:belum_dibayar,dibayar',
            'keterangan'       => 'nullable|string',
        ]);

        $p = Peminjaman::with('barang','pengguna')->findOrFail($validated['peminjaman_id']);

        if ($validated['jenis_denda'] === 'terlambat') {
            $due = Carbon::parse($p->due_at);
            $end = $p->returned_at ? Carbon::parse($p->returned_at) : now();
            $menitTelat = $end->greaterThan($due) ? $end->diffInMinutes($due) : 0;

            if ($menitTelat <= 0) {
                return back()->withErrors(['peminjaman_id'=>'Tidak ada keterlambatan pada peminjaman ini.'])->withInput();
            }

            $validated['total_denda'] = $menitTelat * 1000;
            $validated['keterangan'] = $validated['keterangan'] ?? "Keterlambatan {$menitTelat} menit x Rp1.000";
        } else {
            if (!isset($validated['total_denda']) || $validated['total_denda'] <= 0) {
                return back()->withErrors(['total_denda'=>'Total denda wajib diisi untuk denda hilang.'])->withInput();
            }
        }

        $denda->update($validated);

        return redirect()->route('denda.index')->with('success','Denda berhasil diperbarui.');
    }

    // Hapus
    public function destroy(Denda $denda)
    {
        $denda->delete();
        return redirect()->route('denda.index')->with('success','Denda berhasil dihapus.');
    }
}
