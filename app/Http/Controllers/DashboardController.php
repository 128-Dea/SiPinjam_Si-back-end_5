<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    // ==== DASHBOARD INDEX ========
 
    public function index()
    {
        $user = auth()->user();

        // Jika mahasiswa, arahkan ke dashboard mahasiswa
        if ($user->role === 'mahasiswa') {
            return view('dashboard.mahasiswa.index');
        }

        // Jika petugas, tampilkan dashboard utama
        $totalBarang     = Barang::count();
        $totalKategori   = Kategori::count();
        $totalPeminjaman = Peminjaman::count();
        $totalPengguna   = Pengguna::count();

        return view('dashboard.index', compact(
            'totalBarang',
            'totalKategori',
            'totalPeminjaman',
            'totalPengguna'
        ));
    }


    // ======== BARANG ==========

    public function barang()
    {
        $barang = Barang::paginate(10);
        return view('dashboard.barang.index', compact('barang'));
    }

    public function createBarang()
    {
        $kategori = Kategori::all();
        return view('dashboard.barang.create', compact('kategori'));
    }

    public function storeBarang(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'kategori'    => 'required|string',
            'lokasi'      => 'nullable|string',
            'status'      => 'required|in:tersedia,dipinjam,rusak',
        ]);

        Barang::create($request->all());

        return redirect()
            ->route('dashboard.barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    public function editBarang($id)
    {
        $barang   = Barang::findOrFail($id);
        $kategori = Kategori::all();

        return view('dashboard.barang.edit', compact('barang', 'kategori'));
    }

    public function updateBarang(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'kategori'    => 'required|string',
            'lokasi'      => 'nullable|string',
            'status'      => 'required|in:tersedia,dipinjam,rusak',
        ]);

        $barang->update($request->all());

        return redirect()
            ->route('dashboard.barang.index')
            ->with('success', 'Barang berhasil diperbarui');
    }

    public function destroyBarang($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()
            ->route('dashboard.barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }


    // ======= KATEGORI =========

    public function kategori()
    {
        $kategori = Kategori::paginate(10);
        return view('dashboard.kategori.index', compact('kategori'));
    }

    public function createKategori()
    {
        return view('dashboard.kategori.create');
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
            'deskripsi'     => 'nullable|string',
        ]);

        Kategori::create($request->all());

        return redirect()
            ->route('dashboard.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function editKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('dashboard.kategori.edit', compact('kategori'));
    }

    public function updateKategori(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id,
            'deskripsi'     => 'nullable|string',
        ]);

        $kategori->update($request->all());

        return redirect()
            ->route('dashboard.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroyKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()
            ->route('dashboard.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }


    // ===== PENGGUNA ============

    public function pengguna()
    {
        $pengguna = Pengguna::paginate(10);
        return view('dashboard.pengguna.index', compact('pengguna'));
    }

    public function createPengguna()
    {
        return view('dashboard.pengguna.create');
    }

    public function storePengguna(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:5',
            'nim'      => 'nullable|string',
            'jurusan'  => 'nullable|string',
            'role'     => 'required|in:mahasiswa,petugas',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        Pengguna::create($data);

        return redirect()
            ->route('dashboard.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function editPengguna($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return view('dashboard.pengguna.edit', compact('pengguna'));
    }

    public function updatePengguna(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:pengguna,email,' . $id,
            'password' => 'nullable|string|min:5',
            'nim'      => 'nullable|string',
            'jurusan'  => 'nullable|string',
            // cuma 2 role
            'role'     => 'required|in:mahasiswa,petugas',
        ]);

        $data = $request->all();

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        $pengguna->update($data);

        return redirect()
            ->route('dashboard.pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroyPengguna($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->delete();

        return redirect()
            ->route('dashboard.pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }


    // ======= PEMINJAMAN ===========

    public function peminjaman()
    {
        $peminjamans = Peminjaman::with(['pengguna', 'barang'])->paginate(10);
        return view('dashboard.peminjaman.index', compact('peminjamans'));
    }

    public function createPeminjaman()
    {
        $pengguna = Pengguna::all();
        $barang   = Barang::where('status', 'tersedia')->get();

        return view('dashboard.peminjaman.create', compact('pengguna', 'barang'));
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'pengguna_id'      => 'required|exists:pengguna,id',
            'barang_id'        => 'required|exists:barang,id',
            'tanggal_pinjam'   => 'required|date',
            'tanggal_kembali'  => 'required|date|after:tanggal_pinjam',
            'status'           => 'required|in:pending,dipinjam,dikembalikan',
            'catatan'          => 'nullable|string',
        ]);

        Peminjaman::create($request->all());


        Barang::find($request->barang_id)->update(['status' => 'dipinjam']);

        return redirect()
            ->route('dashboard.peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditambahkan');
    }

    public function editPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $pengguna   = Pengguna::all();
        $barang     = Barang::all();

        return view('dashboard.peminjaman.edit', compact('peminjaman', 'pengguna', 'barang'));
    }

    public function updatePeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $request->validate([
            'pengguna_id'        => 'required|exists:pengguna,id',
            'barang_id'          => 'required|exists:barang,id',
            'tanggal_pinjam'     => 'required|date',
            'tanggal_kembali'    => 'required|date|after:tanggal_pinjam',
            'tanggal_dikembalikan' => 'nullable|date',
            'status'             => 'required|in:pending,dipinjam,dikembalikan',
            'catatan'            => 'nullable|string',
        ]);

        $oldBarangId = $peminjaman->barang_id;

        $peminjaman->update($request->all());

        // kalau ganti barang, barang lama dikembalikan
        if ($oldBarangId != $request->barang_id) {
            Barang::find($oldBarangId)->update(['status' => 'tersedia']);
        }

        // set barang baru sesuai status
        Barang::find($request->barang_id)->update([
            'status' => $request->status === 'dikembalikan' ? 'tersedia' : 'dipinjam',
        ]);

        return redirect()
            ->route('dashboard.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diperbarui');
    }

    public function destroyPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // balikan status barang
        Barang::find($peminjaman->barang_id)->update(['status' => 'tersedia']);

        $peminjaman->delete();

        return redirect()
            ->route('dashboard.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus');
    }
}
