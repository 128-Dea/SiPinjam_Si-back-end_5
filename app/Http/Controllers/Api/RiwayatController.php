public function index()
{
    $riwayats = Riwayat::with(['pengguna', 'riwayatable'])->latest()->get();

    return response()->json([
        'success' => true,
        'message' => 'Daftar riwayat berhasil diambil.',
        'data'    => $riwayats,
    ]);
}

public function show(Riwayat $riwayat)
{
    $riwayat->load(['pengguna','riwayatable']);

    return response()->json([
        'success' => true,
        'message' => 'Detail riwayat berhasil diambil.',
        'data'    => $riwayat,
    ]);
}
