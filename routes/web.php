<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('redirect.authenticated')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//ROUTE ADMIN
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'show'])->name('admin.dashboard');

    Route::prefix('pengguna')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.user');
        Route::get('/tambah', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
        Route::patch('/ubah/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
        Route::post('/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');
        Route::post('/upload', [App\Http\Controllers\Admin\UserController::class, 'upload'])->name('admin.user.upload');
        Route::delete('/delete/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.user.delete');
    });

    Route::prefix('profil')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProfileUserController::class, 'index'])->name('admin.profile.index');
        Route::get('/ubah', [App\Http\Controllers\Admin\ProfileUserController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/update', [App\Http\Controllers\Admin\ProfileUserController::class, 'update'])->name('admin.profile.update');
        Route::get('/delete-foto', [App\Http\Controllers\Admin\ProfileUserController::class, 'delete_foto'])->name('admin.profile.delete-foto');

        // Route::post('/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');
        // Route::post('/upload', [App\Http\Controllers\Admin\UserController::class, 'upload'])->name('admin.user.upload');
        // Route::delete('/delete/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.user.delete');
    });

    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

//ROUTE GURU
Route::group(['prefix' => 'guru', 'middleware' => ['auth', 'role:guru']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'show'])->name('guru.dashboard');

    // Route::prefix('pengguna')->group(function () {
    //     Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.user');
    //     Route::get('/tambah', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
    //     Route::patch('/ubah/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
    //     Route::post('/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');
    //     Route::post('/upload', [App\Http\Controllers\Admin\UserController::class, 'upload'])->name('admin.user.upload');
    //     Route::delete('/delete/{peserta}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.user.delete');
    // });

    // Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    // Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

//ROUTE MURID
Route::group(['prefix' => 'murid', 'middleware' => ['auth', 'role:murid']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Murid\DashboardController::class, 'show'])->name('murid.dashboard');

    // Route::prefix('pengguna')->group(function () {
    //     Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.user');
    //     Route::get('/tambah', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
    //     Route::patch('/ubah/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
    //     Route::post('/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');
    //     Route::post('/upload', [App\Http\Controllers\Admin\UserController::class, 'upload'])->name('admin.user.upload');
    //     Route::delete('/delete/{peserta}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.user.delete');
    // });

    // Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    // Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});
