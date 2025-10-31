<?php

namespace App\Http\Controllers;

use App\Models\SerahTerima;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class SerahTerimaController extends Controller
{
    public function index()
    {
        $serahTerimas = SerahTerima::with(['peminjaman', 'pengguna'])
            ->latest()
            ->paginate(10);

        return view('serah_terima.index', compact('serahTerimas'));
    }

    public function create()
    {
        $peminjamans = Peminjaman::all();
        $penggunas = Pengguna::all();
        return view('serah_terima.create', compact('peminjamans', 'penggunas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'pengguna_id' => 'required|exists:pengguna,id',
            'tanggal_serah_terima' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        SerahTerima::create($request->all());

        return redirect()->route('serah-terima.index')->with('success', 'Data serah terima berhasil ditambahkan.');
    }

    public function show(SerahTerima $serahTerima)
    {
        $serahTerima->load(['peminjaman', 'pengguna']);
        return view('serah_terima.show', compact('serahTerima'));
    }

    public function destroy(SerahTerima $serahTerima)
    {
        $serahTerima->delete();
        return redirect()->route('serah-terima.index')->with('success', 'Data serah terima berhasil dihapus.');
    }
}
