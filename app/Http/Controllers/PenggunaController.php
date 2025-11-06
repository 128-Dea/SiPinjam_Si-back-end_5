<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengguna = Pengguna::all();
        return response()->json($pengguna);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:5',
            'nim' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'role' => 'required|in:mahasiswa,admin',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $pengguna = Pengguna::create($data);
        return response()->json($pengguna, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return response()->json($pengguna);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        $request->validate([
            'nama' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:pengguna,email,' . $id,
            'password' => 'nullable|string|min:5',
            'nim' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'role' => 'required|in:mahasiswa,admin',
        ]);

        $data = $request->all();
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pengguna->update($data);
        return response()->json($pengguna);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->delete();
        return response()->json(['message' => 'Pengguna deleted successfully']);
    }
}
