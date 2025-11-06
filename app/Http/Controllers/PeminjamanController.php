<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengguna;
use App\Models\Barang;
use App\Models\Qr;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'mahasiswa') {
            $peminjamans = Peminjaman::with(['pengguna', 'barang', 'qr'])
                ->where('pengguna_id', auth()->id())
                ->latest()
                ->paginate(10);

            return view('dashboard.peminjaman.index', compact('peminjamans'));
        }

        $peminjamans = Peminjaman::with(['pengguna', 'barang', 'qr'])
            ->latest()
            ->paginate(10);

        return view('dashboard.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        if (auth()->user()->role != 'mahasiswa') {
            return redirect()->route('dashboard.peminjaman.index')
                ->with('error', 'Petugas tidak diperbolehkan menambah peminjaman.');
        }

        $barang = Barang::where('status', 'tersedia')->get();
        return view('dashboard.peminjaman.create', compact('barang'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role != 'mahasiswa') {
            return redirect()->route('dashboard.peminjaman.index')
                ->with('error', 'Petugas tidak diperbolehkan menambah peminjaman.');
        }

        // terima datetime-local
        if ($request->has('tanggal_pinjam')) {
            $request->merge([
                'tanggal_pinjam' => str_replace('T', ' ', $request->tanggal_pinjam),
            ]);
        }
        if ($request->has('tanggal_kembali')) {
            $request->merge([
                'tanggal_kembali' => str_replace('T', ' ', $request->tanggal_kembali),
            ]);
        }

        $validated = $request->validate([
            'barang_id'       => 'required|exists:barang,id',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'catatan'         => 'nullable|string',
        ]);

        $validated['pengguna_id'] = auth()->id();
        $validated['status'] = 'pending';

        Peminjaman::create($validated);

        return redirect()->route('dashboard.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diajukan. Menunggu persetujuan petugas.');
    }

    public function edit(Peminjaman $peminjaman)
    {
        if (auth()->user()->role == 'mahasiswa') {
            return redirect()->route('dashboard.peminjaman.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah data.');
        }

        $barang = Barang::all();
        $pengguna = Pengguna::all();
        return view('dashboard.peminjaman.edit', compact('peminjaman', 'barang', 'pengguna'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        if (auth()->user()->role == 'mahasiswa') {
            return redirect()->route('dashboard.peminjaman.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah data.');
        }

        $validated = $request->validate([
            'status'               => 'required|in:pending,disetujui,ditolak,dipinjam',
            'tanggal_dikembalikan' => 'nullable|date',
            'catatan'              => 'nullable|string',
        ]);

        // urus status barang
        if ($validated['status'] === 'disetujui' || $validated['status'] === 'dipinjam') {
            $peminjaman->barang->update(['status' => 'dipinjam']);
        }

        if ($validated['status'] === 'ditolak') {
            $peminjaman->barang->update(['status' => 'tersedia']);
        }

        if ($validated['status'] === 'dikembalikan') {
            $peminjaman->barang->update(['status' => 'tersedia']);
            $validated['tanggal_dikembalikan'] = now();
        }

        // simpan perubahan
        $peminjaman->update($validated);

        // AUTO QR
        if (in_array($validated['status'], ['disetujui', 'dipinjam'])) {
            if (!$peminjaman->qr) {
                Qr::create([
                    'barang_id'       => $peminjaman->barang_id,
                    'peminjaman_id'   => $peminjaman->id,
                    'serah_terima_id' => null,
                    'tipe'            => 'peminjaman',
                    'kode_qr'         => 'PINJAM-'.$peminjaman->id.'-'.time(),
                    'data_qr'         => json_encode([
                        'jenis'          => 'peminjaman',
                        'peminjaman_id'  => $peminjaman->id,
                        'barang_id'      => $peminjaman->barang_id,
                        'pengguna_id'    => $peminjaman->pengguna_id,
                        'tanggal_pinjam' => optional($peminjaman->tanggal_pinjam)->format('Y-m-d H:i:s'),
                        'tanggal_kembali'=> optional($peminjaman->tanggal_kembali)->format('Y-m-d H:i:s'),
                    ]),
                ]);
            }
        }

        return redirect()->route('dashboard.peminjaman.index')
            ->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if (auth()->user()->role == 'mahasiswa') {
            return redirect()->route('dashboard.peminjaman.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus.');
        }

        $peminjaman->delete();

        return redirect()->route('dashboard.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus');
    }
}
