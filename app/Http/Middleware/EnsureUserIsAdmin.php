<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //
        if (!Auth::check()) {
            
            return redirect()->guest(route('filament.admin.auth.login'));
        }

        
        if (Auth::user()->role !== 'admin') {
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            
            return redirect()->route('filament.admin.auth.login')->withErrors([
                'email' => 'Anda tidak memiliki izin untuk mengakses panel admin.',
            ]);
        }

        return $next($request);
    }
}