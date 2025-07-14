<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- PERBAHAN DI SINI: Pengecekan Role setelah authentication berhasil ---
        if (Auth::user()->role === 'admin') {
            // Jika user adalah admin, logout dia dari sesi frontend
            Auth::guard('web')->logout(); // Pastikan logout dari guard 'web'
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect kembali ke halaman login frontend dengan pesan error
            return redirect()->route('login')->withErrors([
                'email' => 'Akun admin tidak diizinkan login di sini. Silakan login melalui panel admin.',
            ]);
        }
        // --- AKHIR PERBAHAN ---

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}