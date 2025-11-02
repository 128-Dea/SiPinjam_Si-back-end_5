<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user  = Auth::user();
            $email = strtolower($user->email);

            // tentukan role dari domain
            if (str_ends_with($email, '@admin.ac.id')) {
                if ($user->role !== 'petugas') {
                    $user->role = 'petugas';
                    $user->save();
                }
            } elseif (str_ends_with($email, '@mhs.unesa.ac.id')) {
                if ($user->role !== 'mahasiswa') {
                    $user->role = 'mahasiswa';
                    $user->save();
                }
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Domain email tidak diizinkan.']);
            }

            // SELALU ke dashboard.index
            return redirect()->route('dashboard.index');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $email  = strtolower($request->email);
        $domain = substr(strrchr($email, "@"), 1);

        if ($domain === 'admin.ac.id') {
            $role = 'petugas';
        } elseif ($domain === 'mhs.unesa.ac.id') {
            $role = 'mahasiswa';
        } else {
            return back()->withErrors(['email' => 'Gunakan email @admin.ac.id atau @mhs.unesa.ac.id']);
        }

        $user = Pengguna::create([
            'nama'     => $request->nama,
            'email'    => $email,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        Auth::login($user);

        // SELALU ke dashboard.index
        return redirect()->route('dashboard.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
