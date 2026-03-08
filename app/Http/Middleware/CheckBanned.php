<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika akun di-banned
        if (auth()->check() && auth()->user()->is_banned) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Kita kirimkan sinyal "banned_alert" ke halaman login
            return redirect('/login')->with('banned_alert', 'AKUN SISWA TELAH DI BAN !');
        }

        return $next($request);
    }
}