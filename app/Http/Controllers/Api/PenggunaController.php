<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    // === TAMPIL SEMUA PENGGUNA ===
    public function index()
    {
        $pengguna = Pengguna::all();
        return response()->json([
            'status' => true,
            'message' => 'Data pengguna berhasil diambil',
            'data' => $pengguna
        ]);
    }

    // === SIMPAN PENGGUNA BARU ===
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $pengguna = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data pengguna berhasil ditambahkan',
            'data' => $pengguna
        ], 201);
    }

    // === LIHAT DETAIL PENGGUNA ===
    public function show($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengguna tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $pengguna
        ]);
    }

    // === UPDATE DATA PENGGUNA ===
    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengguna tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:pengguna,email,' . $id,
            'password' => 'sometimes|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->filled('password')) {
            $request['password'] = Hash::make($request->password);
        }

        $pengguna->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data pengguna berhasil diperbarui',
            'data' => $pengguna
        ]);
    }

    // === HAPUS PENGGUNA ===
    public function destroy($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengguna tidak ditemukan'
            ], 404);
        }

        $pengguna->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data pengguna berhasil dihapus'
        ]);
    }
}
