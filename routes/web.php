<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();


Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    });

    Route::prefix('profil')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProfileUserController::class, 'index'])->name('profile.index');
        Route::get('/ubah', [App\Http\Controllers\Admin\ProfileUserController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [App\Http\Controllers\Admin\ProfileUserController::class, 'update'])->name('profile.update');
        Route::get('/delete-foto', [App\Http\Controllers\Admin\ProfileUserController::class, 'delete_foto'])->name('profile.delete-foto');
    });

    Route::group(['prefix' => 'pengguna', 'middleware' => ['role:admin,guru']], function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'show'])->name('user');
        Route::get('/tambah', [App\Http\Controllers\UserController::class, 'create'])->name('user.create');
        Route::patch('/ubah/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
        Route::post('/store', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
        Route::post('/upload', [App\Http\Controllers\UserController::class, 'upload'])->name('user.upload');
        Route::delete('/delete/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.delete');
    });
});

Route::get('/landing-page', [App\Http\Controllers\HomeController::class, 'landing_page'])
    ->withoutMiddleware(['auth'])
    ->name('landing-page');