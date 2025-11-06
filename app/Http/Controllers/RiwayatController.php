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
