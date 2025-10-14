<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    // ... rute-rute admin lain
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'show'])->name('admin.dashboard');

    Route::prefix('pengguna')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.user');
        Route::get('/tambah', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
        Route::patch('/ubah/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
        Route::post('/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');
        Route::post('/upload', [App\Http\Controllers\Admin\UserController::class, 'upload'])->name('admin.user.upload');
        Route::delete('/delete/{peserta}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.user.delete');
    });

    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

