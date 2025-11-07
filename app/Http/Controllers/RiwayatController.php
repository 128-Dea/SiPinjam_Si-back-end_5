<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Riwayat;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayats = Riwayat::with(['pengguna','riwayatable'])->latest()->paginate(10);
        return view('riwayat.index', compact('riwayats'));
    }

    public function show(Riwayat $riwayat)
    {
        $riwayat->load(['pengguna','riwayatable']);
        return view('riwayat.show', compact('riwayat'));
    }
}
