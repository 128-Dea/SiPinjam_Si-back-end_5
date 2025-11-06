<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SerahTerima;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Carbon;

class SerahTerimaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mahasiswa: lihat transaksi yang melibatkan dia
        if ($user->role === 'mahasiswa') {
            $serahTerimas = SerahTerima::with(['peminjaman','penggunaLama','penggunaBaru'])
                ->where(function($q) use ($user) {
                    $q->where('pengguna_lama_id', $user->id)
                      ->orWhere('pengguna_baru_id', $user->id);
                })
                ->latest()->get();
        } else {
            // Petugas: boleh lihat semua (read-only)
            $serahTerimas = SerahTerima::with(['peminjaman','penggunaLama','penggunaBaru'])
                ->latest()->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data serah terima berhasil diambil.',
            'data'    => $serahTerimas,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa') {
            return response()->json(['success'=>false,'message'=>'Hanya mahasiswa yang dapat melakukan serah terima.'], 403);
        }

        $validated = $request->validate([
            'peminjaman_id'       => 'required|exists:peminjaman,id',
            'pengguna_baru_id'    => 'required|exists:pengguna,id',
            'tanggal_serah_terima'=> 'nullable|date', // default now()
            'catatan'             => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::with('barang')->findOrFail($validated['peminjaman_id']);

        // Pastikan peminjaman memang milik mahasiswa yang login
        if ($peminjaman->pengguna_id !== $user->id) {
            return response()->json(['success'=>false,'message'=>'Peminjaman bukan milik Anda.'], 403);
        }

        // Buat record serah terima
        $st = new SerahTerima();
        $st->peminjaman_id        = $peminjaman->id;
        $st->pengguna_lama_id     = $user->id;
        $st->pengguna_baru_id     = (int) $validated['pengguna_baru_id'];
        $st->tanggal_serah_terima = isset($validated['tanggal_serah_terima'])
                                    ? Carbon::parse($validated['tanggal_serah_terima'])
                                    : now();
        $st->catatan              = $validated['catatan'] ?? null;
        $st->save();

        // Update peminjaman: alihkan kepemilikan ke pengguna_baru
        $peminjaman->pengguna_id = $st->pengguna_baru_id;
        $peminjaman->save();

        // Generate QR berisi URL transaksi (atau bisa JSON sesuai kebutuhan)
        $payload = url("/api/serah-terima/{$st->id}"); // deep-link API detail
        $png     = QrCode::format('png')->size(360)->margin(1)->generate($payload);

        $dir  = "qrcode/serah_terima";
        $file = "serah_terima_{$st->id}.png";
        Storage::disk('public')->put("{$dir}/{$file}", $png);

        $st->qr_path = "{$dir}/{$file}";
        $st->save();

        return response()->json([
            'success' => true,
            'message' => 'Serah terima berhasil ditambahkan.',
            'data'    => $st->load(['peminjaman','penggunaLama','penggunaBaru']),
            'qr_url'  => asset("storage/{$st->qr_path}"),
        ], 201);
    }

    public function show(SerahTerima $serahTerima)
    {
        $user = Auth::user();
        $serahTerima->load(['peminjaman','penggunaLama','penggunaBaru']);

        if ($user->role === 'mahasiswa' &&
            !in_array($user->id, [$serahTerima->pengguna_lama_id, $serahTerima->pengguna_baru_id], true)) {
            return response()->json(['success'=>false,'message'=>'Anda tidak berhak melihat transaksi ini.'], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail serah terima berhasil diambil.',
            'data'    => $serahTerima,
            'qr_url'  => $serahTerima->qr_path ? asset("storage/{$serahTerima->qr_path}") : null,
        ]);
    }

    // Petugas READ-ONLY → tidak ada update/destroy untuk API ini
}
