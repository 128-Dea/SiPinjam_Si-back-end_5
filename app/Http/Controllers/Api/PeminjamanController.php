<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\Qr;
use Illuminate\Http\Request;
use App\Support\RiwayatLogger;


class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['pengguna', 'barang', 'qr'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar peminjaman berhasil diambil.',
            'data'    => $peminjamans,
        ]);
    }

    public function store(Request $request)
    {
        
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
            'pengguna_id'     => 'required|exists:pengguna,id',
            'barang_id'       => 'required|exists:barang,id',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'catatan'         => 'nullable|string',
        ]);

        // pending dan menunggu persetujuan petugas
        $validated['status'] = 'pending';

        $peminjaman = Peminjaman::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil diajukan. Menunggu persetujuan petugas.',
            'data'    => $peminjaman,
        ], 201);
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['pengguna', 'barang', 'qr']);

        return response()->json([
            'success' => true,
            'message' => 'Detail peminjaman berhasil diambil.',
            'data'    => $peminjaman,
        ]);
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        if ($request->has('tanggal_kembali')) {
            $request->merge([
                'tanggal_kembali' => str_replace('T', ' ', $request->tanggal_kembali),
            ]);
        }
        if ($request->has('tanggal_dikembalikan')) {
            $request->merge([
                'tanggal_dikembalikan' => str_replace('T', ' ', $request->tanggal_dikembalikan),
            ]);
        }

        $validated = $request->validate([
            'tanggal_kembali'      => 'sometimes|required|date|after_or_equal:tanggal_pinjam',
            'tanggal_dikembalikan' => 'nullable|date',
            'status'               => 'sometimes|required|in:pending,disetujui,ditolak,dipinjam',
            'catatan'              => 'nullable|string',
        ]);

        $peminjaman->update($validated);

        // kalau dikembalikan → barang tersedia lagi
        if (isset($validated['status']) && $validated['status'] === 'dikembalikan') {
            Barang::where('id', $peminjaman->barang_id)->update(['status' => 'tersedia']);
        }

        // kalau disetujui / dipinjam → buat QR (kalau belum ada)
        if (isset($validated['status']) && in_array($validated['status'], ['disetujui', 'dipinjam'])) {

            // pastikan status barang juga ikut
            Barang::where('id', $peminjaman->barang_id)->update(['status' => 'dipinjam']);

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
        RiwayatLogger::log(
    $peminjaman,
    'peminjaman.create',
    'Peminjaman barang "'.$peminjaman->barang->nama_barang.'" oleh '.$peminjaman->pengguna->nama
);
RiwayatLogger::log($peminjaman, 'peminjaman.status', 'Status: '.$peminjaman->status);



        return response()->json([
            'success' => true,
            'message' => 'Data peminjaman berhasil diperbarui.',
            'data'    => $peminjaman->load('qr', 'pengguna', 'barang'),
        ]);
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil dihapus.',
        ]);
    }

    public function myPeminjaman(Request $request)
    {
        $userId = $request->user()->id;

        $peminjamans = Peminjaman::with(['barang', 'qr'])
            ->where('pengguna_id', $userId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar peminjaman milik pengguna.',
            'data'    => $peminjamans,
        ]);
    }
}
