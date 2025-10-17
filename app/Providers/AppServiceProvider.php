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
        // ✅ Modifikasi Blade directive di sini
        Blade::if('role', function (string|array $roles) {
            // Selalu pastikan user sudah login
            if (! Auth::check()) {
                return false;
            }

            // Jika inputnya adalah array, gunakan method hasAnyRole()
            if (is_array($roles)) {
                return Auth::user()->hasAnyRole($roles);
            }

            // Jika bukan array (string), gunakan method hasRole() yang lama
            return Auth::user()->hasRole($roles);
        });

        // Bagian View composer ini tidak perlu diubah
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('user', Auth::user());
            } else {
                $view->with('user', null);
            }
        });
    }
}
