<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayats = Riwayat::with('pengguna')->latest()->paginate(10);
        return view('riwayat.index', compact('riwayats'));
    }

    public function show(Riwayat $riwayat)
    {
        $riwayat->load('pengguna');
        return view('riwayat.show', compact('riwayat'));
    }

    public function destroy(Riwayat $riwayat)
    {
        $riwayat->delete();
        return redirect()->route('riwayat.index')->with('success', 'Riwayat berhasil dihapus.');
    }
}
