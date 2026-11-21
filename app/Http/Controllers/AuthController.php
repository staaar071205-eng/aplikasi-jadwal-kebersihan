<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /** Halaman login */
    public function index(): View
    {
        return view('auth.login');
    }

    /** Proses login */
    public function proses(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $berhasil = Auth::attempt([
            'user_email' => $data['user_email'],
            'password' => $data['password'],
        ]);

        if (! $berhasil) {
            return back()
                ->withErrors(['user_email' => 'Email atau password salah.'])
                ->onlyInput('user_email');
        }

        // Cegah session fixation
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /** Logout */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('sukses', 'Anda telah keluar.');
    }
}
