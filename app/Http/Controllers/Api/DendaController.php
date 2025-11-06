<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DendaController extends Controller
{
    public function index()
    {
        return response()->json(
            Denda::with(['peminjaman'])->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id'    => 'required|exists:peminjaman,id',
            'jenis_denda'      => 'required|in:terlambat,hilang',
            // jika hilang -> total_denda wajib diisi oleh petugas
            'total_denda'      => 'nullable|numeric|min:0',
            'keterangan'       => 'nullable|string',
            'status_pembayaran'=> 'nullable|in:belum_dibayar,dibayar',
        ]);

        $peminjaman = Peminjaman::findOrFail($validated['peminjaman_id']);

        // Default
        $statusPembayaran = $validated['status_pembayaran'] ?? 'belum_dibayar';
        $total = (float)($validated['total_denda'] ?? 0);

        if ($validated['jenis_denda'] === 'terlambat') {
            // Tarif: Rp1.000/menit
            $tarifPerMenit = 1000;

            $due = Carbon::parse($peminjaman->due_at);
            $end = $peminjaman->returned_at ? Carbon::parse($peminjaman->returned_at) : now();

            $menitTelat = $end->greaterThan($due) ? $end->diffInMinutes($due) : 0;

            if ($menitTelat <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada keterlambatan. Denda terlambat tidak dapat dibuat.',
                ], 422);
            }

            $total = $menitTelat * $tarifPerMenit;

            $validated['keterangan'] = $validated['keterangan']
                ?? "Keterlambatan {$menitTelat} menit x Rp1.000";
        } else {
            // jenis_denda = hilang → petugas menentukan harga
            if (!isset($validated['total_denda']) || $total <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'total_denda wajib diisi untuk denda hilang.',
                ], 422);
            }
        }

        $denda = Denda::create([
            'peminjaman_id'    => $validated['peminjaman_id'],
            'jenis_denda'      => $validated['jenis_denda'],
            'total_denda'      => $total,
            'status_pembayaran'=> $statusPembayaran,
            'keterangan'       => $validated['keterangan'] ?? null,
        ]);

        return response()->json($denda->load('peminjaman'), 201);
    }

    public function show($id)
    {
        $denda = Denda::with('peminjaman')->findOrFail($id);
        return response()->json($denda);
    }

    public function update(Request $request, $id)
    {
        $denda = Denda::findOrFail($id);

        $validated = $request->validate([
            'jenis_denda'      => 'sometimes|required|in:terlambat,hilang',
            'total_denda'      => 'sometimes|nullable|numeric|min:0',
            'status_pembayaran'=> 'sometimes|required|in:belum_dibayar,dibayar',
            'keterangan'       => 'sometimes|nullable|string',
        ]);

        // Kalau jenis diubah jadi "terlambat", hitung ulang otomatis
        if (($validated['jenis_denda'] ?? null) === 'terlambat') {
            $p = $denda->peminjaman()->first();
            $due = Carbon::parse($p->due_at);
            $end = $p->returned_at ? Carbon::parse($p->returned_at) : now();
            $menitTelat = $end->greaterThan($due) ? $end->diffInMinutes($due) : 0;

            if ($menitTelat <= 0) {
                return response()->json(['success'=>false,'message'=>'Tidak ada keterlambatan.'], 422);
            }

            $validated['total_denda'] = $menitTelat * 1000;
            $validated['keterangan'] = $validated['keterangan']
                ?? "Keterlambatan {$menitTelat} menit x Rp1.000";
        }

        // Jika jenis hilang & total_denda dikosongkan → tolak
        if (($validated['jenis_denda'] ?? $denda->jenis_denda) === 'hilang') {
            if (array_key_exists('total_denda', $validated) && (float)$validated['total_denda'] <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'total_denda harus > 0 untuk denda hilang.',
                ], 422);
            }
        }

        $denda->update($validated);

        return response()->json($denda->fresh('peminjaman'));
    }

    public function destroy($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->delete();
        return response()->json(['message' => 'Denda deleted successfully']);
    }
}
