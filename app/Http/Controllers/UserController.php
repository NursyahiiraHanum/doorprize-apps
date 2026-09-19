<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $currentUser = Auth::user();
        return view('settings', compact('users', 'currentUser'));
    }

    public function settings()
    {
        $users = User::all();
        $currentUser = Auth::user();
        return view('settings', compact('users', 'currentUser'));
    }

    public function updateSettings(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'new_password'     => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('new_password')) {
            if (Hash::check($request->input('current_password', ''), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
            } else {
                return redirect()->back()->with('error', 'Password saat ini salah.');
            }
        }
        $user->save();

        return redirect()->route('settings')->with('success', 'Pengaturan akun berhasil diperbarui.');
    }

    public function deleteAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
        ]);

        if (Hash::check($validated['current_password'], $user->password)) {
            $user->delete();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Akun berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Password saat ini salah.');
        }
    }

    public function create()
    {
        return view('admin.create_user');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:super_admin,admin,user',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('settings')->with('success', 'User ' . $validated['name'] . ' berhasil ditambahkan!');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Anda sendiri secara langsung dari sini.');
        }

        $user->delete();
        return redirect()->route('settings')->with('success', 'User berhasil dihapus.');
    }
}
