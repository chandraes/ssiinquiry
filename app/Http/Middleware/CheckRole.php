<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan user sudah login
        if (! $request->user()) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        // Loop melalui setiap peran yang diizinkan
        foreach ($roles as $role) {
            // Jika user memiliki salah satu dari peran tersebut, izinkan akses
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        // Jika user tidak memiliki peran yang cocok, tolak akses
        abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
    }
}
