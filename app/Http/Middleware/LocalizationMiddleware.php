<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek jika session 'locale' ada
        if (Session::has('locale')) {
            // Atur bahasa aplikasi sesuai nilai dari session
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}
