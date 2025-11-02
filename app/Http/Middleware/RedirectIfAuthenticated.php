<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // ikuti pola Laravel: cek semua guard yang dikirim
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                $user = auth()->guard($guard)->user();

                // kalau mahasiswa → ke dashboard (nanti controller yang pilih view mahasiswa)
                if ($user && $user->role === 'mahasiswa') {
                    return redirect()->route('dashboard.index');
                }

                // admin / petugas → tetap ke dashboard juga
                return redirect()->route('dashboard.index');
                // atau kalau kamu mau pakai konstanta default Laravel:
                // return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
