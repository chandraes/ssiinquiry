<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Blade directive untuk pengecekan role
        Blade::if('role', function (string $role) {
            return Auth::check() && Auth::user()->hasRole($role);
        });

        // ✅ View composer global agar variabel $user selalu tersedia di semua view
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('user', Auth::user());
            } else {
                $view->with('user', null);
            }
        });
    }
}
