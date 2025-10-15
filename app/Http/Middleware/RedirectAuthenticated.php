<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                $role = $user->role; // dari accessor getRoleAttribute()

                if (!$role) {
                    return redirect('/dashboard');
                }

                switch ($role->slug) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'guru':
                        return redirect()->route('guru.dashboard');
                    case 'murid':
                        return redirect()->route('murid.dashboard');
                    default:
                        return redirect('/dashboard');
                }
            }
        }

        return $next($request);
    }

}
