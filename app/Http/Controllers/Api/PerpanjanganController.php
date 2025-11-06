<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perpanjangan;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Support\RiwayatLogger;


class PerpanjanganController extends Controller
{
    // ========= GET: Semua perpanjangan ==========
    public function index()
    {
        if (Auth::user()->role === 'mahasiswa') {
            $perpanjangan = Perpanjangan::with('peminjaman.barang')
                ->whereHas('peminjaman', function ($q) {
                    $q->where('pengguna_id', Auth::id());
                })
                ->latest()
                ->get();
        } else {
            $perpanjangan = Perpanjangan::with('peminjaman.barang', 'peminjaman.pengguna')
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar perpanjangan berhasil diambil.',
            'data'    => $perpanjangan,
        ]);
    }

    // ====== POST: Tambah perpanjangan (Mahasiswa) ==========
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            return response()->json([
                'success' => false,
                'message' => 'Petugas tidak diperbolehkan menambah perpanjangan.',
            ], 403);
        }

        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'alasan'        => 'required|string|max:255',
        ]);

        // Pastikan peminjaman milik user dan masih valid
        $peminjaman = Peminjaman::where('id', $validated['peminjaman_id'])
            ->where('pengguna_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan atau tidak valid.',
            ], 403);
        }

        $validated['tanggal_perpanjangan'] = now();
        $validated['status'] = 'pending';

        $perpanjangan = Perpanjangan::create($validated)->load('peminjaman.barang', 'peminjaman.pengguna');
        RiwayatLogger::log(
    $perpanjangan,
    'perpanjangan.create',
    'Ajukan perpanjangan peminjaman #'.$perpanjangan->peminjaman_id
);


        // === Notifikasi ke PETUGAS: ada pengajuan perpanjangan baru ===
        Notifikasi::create([
            'pengguna_id' => null,
            'barang_id'   => $peminjaman->barang_id,
            'judul'       => 'Pengajuan Perpanjangan Baru',
            'pesan'       => 'Mahasiswa '.$perpanjangan->peminjaman->pengguna->nama.
                             ' mengajukan perpanjangan untuk "'.$perpanjangan->peminjaman->barang->nama_barang.'".',
            'tipe'        => 'info',
            'role_target' => 'petugas',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Perpanjangan berhasil diajukan. Menunggu persetujuan petugas.',
            'data'    => $perpanjangan,
        ], 201);
    }

    // ======= GET: Detail perpanjangan ========
    public function show(Perpanjangan $perpanjangan)
    {
        $perpanjangan->load('peminjaman.barang', 'peminjaman.pengguna');

        // mahasiswa hanya boleh lihat miliknya
        if (Auth::user()->role === 'mahasiswa'
            && $perpanjangan->peminjaman->pengguna_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail perpanjangan berhasil diambil.',
            'data'    => $perpanjangan,
        ]);
    }

    // ====== PUT/PATCH: Update status (Petugas) ======
    public function update(Request $request, Perpanjangan $perpanjangan)
    {
        if (Auth::user()->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang bisa mengubah status perpanjangan.',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            // opsional: berapa menit diperpanjang, default 60
            'durasi_menit' => 'nullable|integer|min:1|max:10080',
        ]);

        $perpanjangan->update([
            'status' => $validated['status'],
        ]);

        $perpanjangan->load('peminjaman.barang', 'peminjaman.pengguna');

        // === Jika disetujui: update due_at & kirim notifikasi ke MAHASISWA ===
        if ($validated['status'] === 'disetujui') {
            $durasi = $validated['durasi_menit'] ?? 60;
            $p = $perpanjangan->peminjaman;

            // update due_at (+ durasi menit)
            if (!empty($p->due_at)) {
                $p->due_at = Carbon::parse($p->due_at)->addMinutes($durasi);
                $p->save();
            }
            RiwayatLogger::log(
    $perpanjangan,
    'perpanjangan.update',
    'Status perpanjangan #'.$perpanjangan->id.' => '.$perpanjangan->status
);


            Notifikasi::create([
                'pengguna_id' => $p->pengguna_id,
                'barang_id'   => $p->barang_id,
                'judul'       => 'Perpanjangan Berhasil',
                'pesan'       => 'Waktu peminjaman untuk "'.$p->barang->nama_barang.
                                 '" diperpanjang '.$durasi.' menit. Jatuh tempo baru: '.
                                 Carbon::parse($p->due_at)->format('Y-m-d H:i').'.',
                'tipe'        => 'info',
                'role_target' => 'mahasiswa',
            ]);
        } else {
            // === Ditolak: notifikasi ke MAHASISWA ===
            $p = $perpanjangan->peminjaman;
            Notifikasi::create([
                'pengguna_id' => $p->pengguna_id,
                'barang_id'   => $p->barang_id,
                'judul'       => 'Perpanjangan Ditolak',
                'pesan'       => 'Pengajuan perpanjangan untuk "'.$p->barang->nama_barang.'" ditolak petugas.',
                'tipe'        => 'error',
                'role_target' => 'mahasiswa',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status perpanjangan berhasil diperbarui.',
            'data'    => $perpanjangan,
        ]);
    }

    // ========= DELETE: Hapus (Petugas) =======
    public function destroy(Perpanjangan $perpanjangan)
    {
        if (Auth::user()->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya petugas yang bisa menghapus perpanjangan.',
            ], 403);
        }

        $perpanjangan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data perpanjangan berhasil dihapus.',
        ]);
    }
}
