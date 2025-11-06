<?php

namespace App\Http\Controllers;

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

        if ($user->role === 'mahasiswa') {
            $serahTerimas = SerahTerima::with(['peminjaman','penggunaLama','penggunaBaru'])
                ->where(function($q) use ($user){
                    $q->where('pengguna_lama_id',$user->id)
                      ->orWhere('pengguna_baru_id',$user->id);
                })
                ->latest()->paginate(10);
        } else {
            // Petugas lihat semua
            $serahTerimas = SerahTerima::with(['peminjaman','penggunaLama','penggunaBaru'])
                ->latest()->paginate(10);
        }

        return view('serah_terima.index', compact('serahTerimas'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa') {
            return redirect()->route('serah-terima.index')->with('error','Petugas tidak dapat membuat serah terima.');
        }

        // hanya peminjaman milik mahasiswa saat ini
        $peminjamans = Peminjaman::with(['barang'])
            ->where('pengguna_id', $user->id)
            ->whereIn('status', ['dipinjam','disetujui']) // sesuaikan statusmu
            ->latest()->get();

        // calon pengguna baru (mahasiswa lain)
        $mahasiswas = Pengguna::where('role','mahasiswa')
            ->where('id','!=',$user->id)
            ->orderBy('nama')->get();

        return view('serah_terima.create', compact('peminjamans','mahasiswas'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa') {
            return redirect()->route('serah-terima.index')->with('error','Petugas tidak dapat membuat serah terima.');
        }

        $validated = $request->validate([
            'peminjaman_id'        => 'required|exists:peminjaman,id',
            'pengguna_baru_id'     => 'required|exists:pengguna,id',
            'tanggal_serah_terima' => 'nullable|date',
            'catatan'              => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::with('barang')->findOrFail($validated['peminjaman_id']);
        if ($peminjaman->pengguna_id !== $user->id) {
            return redirect()->back()->withErrors(['peminjaman_id'=>'Peminjaman bukan milik Anda.'])->withInput();
        }

        $st = new SerahTerima();
        $st->peminjaman_id        = $peminjaman->id;
        $st->pengguna_lama_id     = $user->id;
        $st->pengguna_baru_id     = (int) $validated['pengguna_baru_id'];
        $st->tanggal_serah_terima = isset($validated['tanggal_serah_terima'])
                                    ? Carbon::parse($validated['tanggal_serah_terima'])
                                    : now();
        $st->catatan              = $validated['catatan'] ?? null;
        $st->save();

        // Alihkan kepemilikan peminjaman ke pengguna_baru
        $peminjaman->pengguna_id = $st->pengguna_baru_id;
        $peminjaman->save();

        // Generate QR
        $payload = url("/dashboard/serah-terima/{$st->id}"); // bisa juga pakai url API
        $png     = QrCode::format('png')->size(360)->margin(1)->generate($payload);

        $dir  = "qrcode/serah_terima";
        $file = "serah_terima_{$st->id}.png";
        Storage::disk('public')->put("{$dir}/{$file}", $png);

        $st->qr_path = "{$dir}/{$file}";
        $st->save();

        // Setelah sukses, langsung tampilkan halaman detail (QR muncul)
        return redirect()->route('serah-terima.show', $st->id)
            ->with('success','Serah terima berhasil dibuat.');
    }

    public function show(SerahTerima $serahTerima)
    {
        $serahTerima->load(['peminjaman','penggunaLama','penggunaBaru']);
        return view('serah_terima.show', compact('serahTerima'));
    }

    // Petugas READ-ONLY: tidak ada edit/update/destroy.
    // Kalau route resource sudah ada method tsb, beri guard:
    public function edit()
    {
        return redirect()->route('serah-terima.index')->with('error','Aksi ini tidak diizinkan.');
    }
    public function update()
    {
        return redirect()->route('serah-terima.index')->with('error','Aksi ini tidak diizinkan.');
    }
    public function destroy()
    {
        return redirect()->route('serah-terima.index')->with('error','Aksi ini tidak diizinkan.');
    }
}
