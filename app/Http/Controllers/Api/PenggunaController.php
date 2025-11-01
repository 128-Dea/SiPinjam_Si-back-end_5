<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    // ============================
    // GET /api/pengguna
    // ============================
    public function index()
    {
        $pengguna = Pengguna::all();

        return response()->json([
            'status'  => true,
            'message' => 'Data pengguna berhasil diambil',
            'data'    => $pengguna,
        ]);
    }

    // ============================
    // POST /api/pengguna
    // ============================
    public function store(Request $request)
    {
        // validasi input
        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:5',
            'nim'      => 'nullable|string|max:50',
            'jurusan'  => 'nullable|string|max:100',
            'role'     => 'nullable|in:mahasiswa,petugas,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // karena di model kita pakai 'password' => 'hashed',
        // cukup kirim plain password, NANTI otomatis di-hash oleh model
        $pengguna = Pengguna::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => $request->password,
            'nim'      => $request->nim,
            'jurusan'  => $request->jurusan,
            'role'     => $request->role ?? 'mahasiswa',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Data pengguna berhasil ditambahkan',
            'data'    => $pengguna,
        ], 201);
    }

    // ============================
    // GET /api/pengguna/{id}
    // ============================
    public function show($id)
    {
        // kalau model pakai primaryKey = 'id_pengguna'
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
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

    // ============================
    // PUT/PATCH /api/pengguna/{id}
    // ============================
    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Data pengguna tidak ditemukan',
            ], 404);
        }

        // validasi input dinamis
        // note: unique email harus ignore email user ini sendiri
        // di sini kita asumsikan primary key adalah id_pengguna
        $validator = Validator::make($request->all(), [
            'nama'     => 'sometimes|string|max:100',
            'email'    => 'sometimes|email|unique:pengguna,email,' . $pengguna->getKey() . ',' . $pengguna->getKeyName(),
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

        // kita rakit data update manual supaya aman
        $dataUpdate = [];

        if ($request->filled('nama')) {
            $dataUpdate['nama'] = $request->nama;
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

        $pengguna->update($dataUpdate);

        return response()->json([
            'status'  => true,
            'message' => 'Data pengguna berhasil diperbarui',
            'data'    => $pengguna,
        ]);
    }

    // ============================
    // DELETE /api/pengguna/{id}
    // ============================
    public function destroy($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
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
