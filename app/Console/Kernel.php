<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Model yang dibutuhkan scheduler
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Daftarkan jadwal tugas di sini.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Task bawaan agar terlihat di "schedule:list" (opsional; hapus kalau tak perlu)
        $schedule->command('inspire')->everyMinute();

        // === NOTIFIKASI OTOMATIS SETIAP MENIT ===
        $schedule->call(function () {
            $now = now();

            // 1) Pengingat 30 menit sebelum jatuh tempo (MAHASISWA)
            $from = $now->copy()->addMinutes(30);
            $to   = $now->copy()->addMinutes(31);

            $reminders = Peminjaman::with(['barang','pengguna'])
                ->whereNull('returned_at')
                ->whereBetween('due_at', [$from, $to])
                ->get();

            foreach ($reminders as $p) {
                // Hindari spam: cek ada notifikasi serupa dalam 60 menit terakhir
                $exists = Notifikasi::where([
                        'role_target' => 'mahasiswa',
                        'pengguna_id' => $p->pengguna_id,
                        'barang_id'   => $p->barang_id,
                        'tipe'        => 'info',
                        'judul'       => 'Pengingat Pengembalian',
                    ])
                    ->where('created_at', '>=', now()->subMinutes(60))
                    ->exists();

                if (!$exists) {
                    Notifikasi::create([
                        'pengguna_id' => $p->pengguna_id,
                        'barang_id'   => $p->barang_id,
                        'judul'       => 'Pengingat Pengembalian',
                        'pesan'       => 'Pengembalian barang "'.$p->barang->nama_barang.
                                        '" jatuh tempo pukul '.Carbon::parse($p->due_at)->format('H:i').
                                        ' (±30 menit lagi).',
                        'tipe'        => 'info',
                        'role_target' => 'mahasiswa',
                    ]);
                }
            }

            // 2) Peminjaman telat (PETUGAS)
            $late = Peminjaman::with(['barang','pengguna'])
                ->whereNull('returned_at')
                ->where('due_at', '<', $now)
                ->get();

            foreach ($late as $p) {
                $exists = Notifikasi::where([
                        'role_target' => 'petugas',
                        'pengguna_id' => null,
                        'barang_id'   => $p->barang_id,
                        'tipe'        => 'warning',
                        'judul'       => 'Peminjaman Terlambat',
                    ])
                    ->where('created_at', '>=', now()->subMinutes(30))
                    ->exists();

                if (!$exists) {
                    $menitTelat = Carbon::parse($p->due_at)->diffInMinutes($now);
                    Notifikasi::create([
                        'pengguna_id' => null, // broadcast ke petugas
                        'barang_id'   => $p->barang_id,
                        'judul'       => 'Peminjaman Terlambat',
                        'pesan'       => 'Mahasiswa '.$p->pengguna->nama.' terlambat mengembalikan "'.
                                         $p->barang->nama_barang.'" (telat '.$menitTelat.' menit).',
                        'tipe'        => 'warning',
                        'role_target' => 'petugas',
                    ]);
                }
            }

            // 3) Denda belum dibayar (MAHASISWA + PETUGAS)
            $dendaUnpaid = Denda::with(['peminjaman.barang','pengguna'])
                ->where('status', 'unpaid')
                ->get();

            foreach ($dendaUnpaid as $d) {
                // Mahasiswa
                $existsM = Notifikasi::where([
                        'role_target' => 'mahasiswa',
                        'pengguna_id' => $d->pengguna_id,
                        'barang_id'   => optional($d->peminjaman)->barang_id,
                        'tipe'        => 'warning',
                        'judul'       => 'Tagihan Denda',
                    ])
                    ->where('created_at', '>=', now()->subHours(6))
                    ->exists();

                if (!$existsM) {
                    Notifikasi::create([
                        'pengguna_id' => $d->pengguna_id,
                        'barang_id'   => optional($d->peminjaman)->barang_id,
                        'judul'       => 'Tagihan Denda',
                        'pesan'       => 'Anda memiliki denda sebesar Rp '.
                                          number_format($d->jumlah,0,',','.').
                                          ' untuk peminjaman #'.$d->peminjaman_id.'.',
                        'tipe'        => 'warning',
                        'role_target' => 'mahasiswa',
                    ]);
                }

                // Petugas
                $existsP = Notifikasi::where([
                        'role_target' => 'petugas',
                        'pengguna_id' => null,
                        'barang_id'   => optional(optional($d->peminjaman)->barang)->id,
                        'tipe'        => 'info',
                        'judul'       => 'Denda Belum Dibayar',
                    ])
                    ->where('created_at', '>=', now()->subHours(6))
                    ->exists();

                if (!$existsP) {
                    Notifikasi::create([
                        'pengguna_id' => null,
                        'barang_id'   => optional(optional($d->peminjaman)->barang)->id,
                        'judul'       => 'Denda Belum Dibayar',
                        'pesan'       => 'Mahasiswa '.$d->pengguna->nama.' belum membayar denda #'.
                                         $d->id.' (Rp '.number_format($d->jumlah,0,',','.').').',
                        'tipe'        => 'info',
                        'role_target' => 'petugas',
                    ]);
                }
            }

        })->everyMinute();
    }

    /**
     * Daftarkan command Artisan kustom (jika ada).
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
