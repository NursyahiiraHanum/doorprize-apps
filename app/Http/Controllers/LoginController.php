<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Auto-seed default users if database is empty
        if (User::count() === 0) {
            User::create([
                'name'     => 'Super Admin Voyages',
                'email'    => 'superadmin@voyages.com',
                'password' => Hash::make('admin123'),
                'role'     => 'super_admin',
            ]);
        }

        // Jika user sudah login, langsung ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // Proses autentikasi dan pengecekan rate limit dari LoginRequest
        $request->authenticate();

        // Regenerasi session (Mencegah Session Fixation Hack)
        $request->session()->regenerate();

        // Semua role masuk ke dashboard — sidebar yang mengatur akses visual
        return redirect()->intended(route('dashboard'));
    }

    // Memproses logout
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
