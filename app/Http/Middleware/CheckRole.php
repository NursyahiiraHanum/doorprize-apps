<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role ?? 'user';

        // Super Admin memiliki akses ke seluruh fitur tanpa batas
        if ($userRole === 'super_admin') {
            return $next($request);
        }

        // Cek apakah role pengguna diizinkan
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Jika user (Operator Absensi) mencoba akses halaman lain, arahkan ke Absensi
        if ($userRole === 'user') {
            return redirect()->route('dashboard')->with('error', 'Akses dibatasi. Anda hanya diizinkan mengakses menu Daftar Kehadiran.');
        }

        // Jika admin biasa mencoba akses halaman khusus Super Admin
        if ($userRole === 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses dibatasi. Halaman ini khusus untuk Super Admin.');
        }

        return redirect()->route('login');
    }
}
