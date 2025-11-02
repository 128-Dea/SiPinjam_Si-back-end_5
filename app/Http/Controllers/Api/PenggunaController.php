<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
<<<<<<< HEAD
=======
use Illuminate\Http\Request;
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PenggunaController extends Controller
{
<<<<<<< HEAD
    // ============================
    // GET /api/pengguna
    // ============================
=======
    // =========================================
    // GET /api/pengguna
    // =========================================
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
    public function index()
    {
        $pengguna = Pengguna::all();

        return response()->json([
            'status'  => true,
            'message' => 'Data pengguna berhasil diambil',
            'data'    => $pengguna,
        ]);
    }

<<<<<<< HEAD
    // ============================
    // POST /api/pengguna
    // ============================
=======
    // =========================================
    // POST /api/pengguna
    // (bisa dari Flutter, bisa dari admin)
    // =========================================
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
    public function store(Request $request)
    {
        // validasi input
        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:5',
            'nim'      => 'nullable|string|max:50',
            'jurusan'  => 'nullable|string|max:100',
<<<<<<< HEAD
=======
            // bisa kosong → kita tebak dari domain
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
            'role'     => 'nullable|in:mahasiswa,petugas,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

<<<<<<< HEAD
        // karena di model kita pakai 'password' => 'hashed',
        // cukup kirim plain password, NANTI otomatis di-hash oleh model
        $pengguna = Pengguna::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => $request->password,
            'nim'      => $request->nim,
            'jurusan'  => $request->jurusan,
            'role'     => $request->role ?? 'mahasiswa',
=======
        $email  = $request->email;
        $domain = Str::after($email, '@');

        // ====== TENTUKAN ROLE OTOMATIS DARI DOMAIN ======
        if (! $request->filled('role')) {
            if ($domain === 'admin.ac.id') {
                $role = 'petugas';
            } elseif ($domain === 'mhs.unesa.ac.id') {
                $role = 'mahasiswa';
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Gunakan email @admin.ac.id atau @mhs.unesa.ac.id',
                ], 403);
            }
        } else {
            // role dikirim manual → tetap cek domainnya
            if ($request->role === 'petugas' && $domain !== 'admin.ac.id') {
                return response()->json([
                    'status'  => false,
                    'message' => 'Role petugas wajib pakai email @admin.ac.id',
                ], 403);
            }
            if ($request->role === 'mahasiswa' && $domain !== 'mhs.unesa.ac.id') {
                return response()->json([
                    'status'  => false,
                    'message' => 'Role mahasiswa wajib pakai email @mhs.unesa.ac.id',
                ], 403);
            }
            $role = $request->role;
        }

        // KAMU PUNYA 2 PILIHAN HASH:
        // 1) kalau di model kamu sudah: 'password' => 'hashed' → boleh kirim plain
        // 2) kalau belum → pakai Hash::make
        // aku buatkan pakai Hash::make biar aman di semua kondisi
        $pengguna = Pengguna::create([
            'nama'     => $request->nama,
            'email'    => $email,
            'password' => Hash::make($request->password),
            'nim'      => $request->nim,
            'jurusan'  => $request->jurusan,
            'role'     => $role,
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Data pengguna berhasil ditambahkan',
            'data'    => $pengguna,
        ], 201);
    }

<<<<<<< HEAD
    // ============================
    // GET /api/pengguna/{id}
    // ============================
    public function show($id)
    {
        // kalau model pakai primaryKey = 'id_pengguna'
=======
    // =========================================
    // GET /api/pengguna/{id}
    // =========================================
    public function show($id)
    {
        // kalau PK kamu id_pengguna, di model jangan lupa set:
        // protected $primaryKey = 'id_pengguna';
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
        $pengguna = Pengguna::find($id);

        if (! $pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Data pengguna tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $pengguna,
        ]);
    }

<<<<<<< HEAD
    // ============================
    // PUT/PATCH /api/pengguna/{id}
    // ============================
=======
    // =========================================
    // PUT/PATCH /api/pengguna/{id}
    // =========================================
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::find($id);

        if (! $pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Data pengguna tidak ditemukan',
            ], 404);
        }

<<<<<<< HEAD
        // validasi input dinamis
        // note: unique email harus ignore email user ini sendiri
        // di sini kita asumsikan primary key adalah id_pengguna
        $validator = Validator::make($request->all(), [
            'nama'     => 'sometimes|string|max:100',
            'email'    => 'sometimes|email|unique:pengguna,email,' . $pengguna->getKey() . ',' . $pengguna->getKeyName(),
=======
        // pakai getKey biar aman kalau PK bukan "id"
        $currentId   = $pengguna->getKey();
        $currentPk   = $pengguna->getKeyName(); // misal: id_pengguna

        $validator = Validator::make($request->all(), [
            'nama'     => 'sometimes|string|max:100',
            // unique:pengguna,email,{id},{primaryKey}
            'email'    => 'sometimes|email|unique:pengguna,email,' . $currentId . ',' . $currentPk,
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
            'password' => 'sometimes|string|min:5',
            'nim'      => 'sometimes|nullable|string|max:50',
            'jurusan'  => 'sometimes|nullable|string|max:100',
            'role'     => 'sometimes|in:mahasiswa,petugas,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

<<<<<<< HEAD
        // kita rakit data update manual supaya aman
=======
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
        $dataUpdate = [];

        if ($request->filled('nama')) {
            $dataUpdate['nama'] = $request->nama;
<<<<<<< HEAD
        }
        if ($request->filled('email')) {
            $dataUpdate['email'] = $request->email;
        }
        if ($request->filled('password')) {
            // cukup assign plain password -> auto hash via casts model
            $dataUpdate['password'] = $request->password;
        }
        if ($request->has('nim')) { // pakai has() karena boleh null
            $dataUpdate['nim'] = $request->nim;
        }
        if ($request->has('jurusan')) {
            $dataUpdate['jurusan'] = $request->jurusan;
        }
        if ($request->filled('role')) {
            $dataUpdate['role'] = $request->role;
        }

=======
        }

        if ($request->filled('email')) {
            $newEmail  = $request->email;
            $newDomain = Str::after($newEmail, '@');

            // kalau role gak dikirim → ikutin domain
            if (! $request->filled('role')) {
                if ($newDomain === 'admin.ac.id') {
                    $dataUpdate['role'] = 'petugas';
                } elseif ($newDomain === 'mhs.unesa.ac.id') {
                    $dataUpdate['role'] = 'mahasiswa';
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Gunakan email @admin.ac.id atau @mhs.unesa.ac.id',
                    ], 403);
                }
            } else {
                // role dikirim, cek kesesuaian
                if ($request->role === 'petugas' && $newDomain !== 'admin.ac.id') {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Role petugas wajib pakai email @admin.ac.id',
                    ], 403);
                }
                if ($request->role === 'mahasiswa' && $newDomain !== 'mhs.unesa.ac.id') {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Role mahasiswa wajib pakai email @mhs.unesa.ac.id',
                    ], 403);
                }
            }

            $dataUpdate['email'] = $newEmail;
        }

        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        if ($request->has('nim')) {
            $dataUpdate['nim'] = $request->nim;
        }

        if ($request->has('jurusan')) {
            $dataUpdate['jurusan'] = $request->jurusan;
        }

        if ($request->filled('role')) {
            $dataUpdate['role'] = $request->role;
        }

>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
        $pengguna->update($dataUpdate);

        return response()->json([
            'status'  => true,
            'message' => 'Data pengguna berhasil diperbarui',
            'data'    => $pengguna,
        ]);
    }

<<<<<<< HEAD
    // ============================
    // DELETE /api/pengguna/{id}
    // ============================
=======
    // =========================================
    // DELETE /api/pengguna/{id}
    // =========================================
>>>>>>> 2e2579466ebfe6f991ffa1eb5d11753c4d2af08c
    public function destroy($id)
    {
        $pengguna = Pengguna::find($id);

        if (! $pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Data pengguna tidak ditemukan',
            ], 404);
        }

        $pengguna->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data pengguna berhasil dihapus',
        ]);
    }
}
