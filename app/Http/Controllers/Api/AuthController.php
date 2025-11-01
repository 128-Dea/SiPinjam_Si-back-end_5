<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * REGISTER
     * - hanya boleh @admin.ac.id dan @mhs.unesa.ac.id
     * - role ditentukan dari domain
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:pengguna,email',
            'password'              => 'required|string|min:5|confirmed',
            // opsional untuk mahasiswa
            'nim'                   => 'nullable|string',
            'jurusan'               => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $email  = $request->email;
        $domain = Str::after($email, '@');

        // cek domain dan tentukan role
        if ($domain === 'admin.ac.id') {
            $role = 'petugas';
        } elseif ($domain === 'mhs.unesa.ac.id') {
            $role = 'mahasiswa';
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Gunakan email kampus: @admin.ac.id atau @mhs.unesa.ac.id',
            ], 403);
        }

        $user = Pengguna::create([
            'nama'     => $request->nama,
            'email'    => $email,
            'password' => Hash::make($request->password),
            'nim'      => $request->nim,
            'jurusan'  => $request->jurusan,
            'role'     => $role,
        ]);

        // token Sanctum
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil',
            'data'    => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * LOGIN
     * - cek email & password
     * - cek domain → paksa role
     * - balikin role + redirect_to biar Flutter bisa bedain halaman
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $email  = $request->email;
        $domain = Str::after($email, '@');

        // cek domain dulu
        if ($domain !== 'admin.ac.id' && $domain !== 'mhs.unesa.ac.id') {
            return response()->json([
                'status'  => false,
                'message' => 'Domain email tidak diizinkan',
            ], 403);
        }

        // cari user
        $user = Pengguna::where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'Akun belum terdaftar',
            ], 404);
        }

        // cek password
        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // sinkronkan role dengan domain (jaga-jaga kalau di DB beda)
        if ($domain === 'admin.ac.id' && $user->role !== 'petugas') {
            $user->role = 'petugas';
            $user->save();
        } elseif ($domain === 'mhs.unesa.ac.id' && $user->role !== 'mahasiswa') {
            $user->role = 'mahasiswa';
            $user->save();
        }

        // buat token baru
        $token = $user->createToken('api-token')->plainTextToken;

        // tentukan tujuan redirect untuk client (Flutter)
        $redirectTo = $user->role === 'petugas'
            ? 'dashboard_petugas'
            : 'dashboard_mahasiswa';

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => [
                'user'        => $user,
                'token'       => $token,
                'redirect_to' => $redirectTo,
            ],
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil',
        ]);
    }
}
