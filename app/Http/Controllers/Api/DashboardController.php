<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * GET /api/dashboard
     * Menentukan dashboard berdasarkan role user yang login
     */
    public function index(Request $request)
    {
        $user = $request->user(); // dari Sanctum

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        // kalau petugas/admin
        if (in_array($user->role, ['petugas', 'admin'])) {
            return $this->adminDashboard($user);
        }

        // default: mahasiswa
        return $this->mahasiswaDashboard($user);
    }

    /**
     * Dashboard untuk PETUGAS / ADMIN
     */
    protected function adminDashboard($user)
    {
        // total2
        $totalBarang     = Barang::count();
        $totalKategori   = Kategori::count();
        $totalPeminjaman = Peminjaman::count();
        $totalPengguna   = Pengguna::count();

        // chart peminjaman 6 bulan terakhir
        $chart = $this->getPeminjamanPerMonth(6);

        // peminjaman terbaru
        $recentPeminjaman = Peminjaman::with(['pengguna', 'barang'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'status'  => true,
            'role'    => $user->role,
            'message' => 'Dashboard petugas',
            'data'    => [
                'summary' => [
                    'total_barang'     => $totalBarang,
                    'total_kategori'   => $totalKategori,
                    'total_peminjaman' => $totalPeminjaman,
                    'total_pengguna'   => $totalPengguna,
                ],
                'chart_peminjaman' => $chart,
                'recent_peminjaman' => $recentPeminjaman,
            ],
        ]);
    }

    /**
     * Dashboard untuk MAHASISWA
     */
    protected function mahasiswaDashboard($user)
    {
        // barang tersedia
        $barangTersedia = Barang::where('status', 'tersedia')
            ->orderBy('nama_barang')
            ->take(20)
            ->get();

        // kategori (kalau mau ditampilkan kayak grid)
        $kategori = Kategori::orderBy('nama_kategori')->get();

        // peminjaman milik user ini saja
        $riwayatPeminjaman = Peminjaman::with('barang')
            ->where('pengguna_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // menu yang mau kamu tampilkan di Flutter
        $menu = [
            [
                'key'   => 'peminjaman',
                'title' => 'Peminjaman',
                'icon'  => 'ri-file-list-line',
                'route' => '/peminjaman', // ini nanti di Flutter-mu aja yang atur
            ],
            [
                'key'   => 'pengembalian',
                'title' => 'Pengembalian',
                'icon'  => 'ri-arrow-go-back-line',
                'route' => '/pengembalian',
            ],
            [
                'key'   => 'perpanjangan',
                'title' => 'Perpanjangan',
                'icon'  => 'ri-time-line',
                'route' => '/perpanjangan',
            ],
            [
                'key'   => 'barang',
                'title' => 'Lihat Barang',
                'icon'  => 'ri-apps-line',
                'route' => '/barang',
            ],
        ];

        return response()->json([
            'status'  => true,
            'role'    => $user->role,
            'message' => 'Dashboard mahasiswa',
            'data'    => [
                'menu'               => $menu,
                'barang_tersedia'    => $barangTersedia,
                'kategori'           => $kategori,
                'riwayat_peminjaman' => $riwayatPeminjaman,
            ],
        ]);
    }

    /**
     * Utility: ambil data peminjaman per bulan x bulan terakhir
     * output: [{month: '2025-07', total: 5}, ...]
     */
    protected function getPeminjamanPerMonth($months = 6)
    {
        $results = [];
        $now     = Carbon::now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $start = $now->copy()->subMonths($i)->startOfMonth();
            $end   = $now->copy()->subMonths($i)->endOfMonth();

            $count = Peminjaman::whereBetween('created_at', [$start, $end])->count();

            $results[] = [
                'month' => $start->format('Y-m'),
                'label' => $start->translatedFormat('M Y'),
                'total' => $count,
            ];
        }

        return $results;
    }

    /**
     * Kalau kamu mau pecah-pecah:
     * GET /api/dashboard/admin
     * GET /api/dashboard/mahasiswa
     * tinggal panggil method di atas
     */
    public function admin(Request $request)
    {
        return $this->adminDashboard($request->user());
    }

    public function mahasiswa(Request $request)
    {
        return $this->mahasiswaDashboard($request->user());
    }
}
