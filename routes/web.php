<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\LoginController;


// ✅ Route bisa diakses tanpa login
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->withoutMiddleware(['auth'])
    ->name('home');

Route::get('/register', [App\Http\Controllers\HomeController::class, 'index'])
    ->withoutMiddleware(['auth'])
    ->name('register');

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Middleware untuk halaman login
Route::middleware('redirect.authenticated')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Auth::routes();

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
    });

    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

//ROUTE GURU
Route::group(['prefix' => 'guru', 'middleware' => ['auth', 'role:guru']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'show'])->name('guru.dashboard');

    Route::prefix('profil')->group(function () {
        Route::get('/', [App\Http\Controllers\Guru\ProfileUserController::class, 'index'])->name('guru.profile.index');
        Route::get('/ubah', [App\Http\Controllers\Guru\ProfileUserController::class, 'edit'])->name('guru.profile.edit');
        Route::put('/update', [App\Http\Controllers\Guru\ProfileUserController::class, 'update'])->name('guru.profile.update');
        Route::get('/delete-foto', [App\Http\Controllers\Guru\ProfileUserController::class, 'delete_foto'])->name('guru.profile.delete-foto');
    });
    

    // Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    // Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

//ROUTE MURID
Route::group(['prefix' => 'murid', 'middleware' => ['auth', 'role:murid']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Murid\DashboardController::class, 'show'])->name('murid.dashboard');

    Route::prefix('profil')->group(function () {
        Route::get('/', [App\Http\Controllers\Murid\ProfileUserController::class, 'index'])->name('murid.profile.index');
        Route::get('/ubah', [App\Http\Controllers\Murid\ProfileUserController::class, 'edit'])->name('murid.profile.edit');
        Route::put('/update', [App\Http\Controllers\Murid\ProfileUserController::class, 'update'])->name('murid.profile.update');
        Route::get('/delete-foto', [App\Http\Controllers\Murid\ProfileUserController::class, 'delete_foto'])->name('murid.profile.delete-foto');
    });

    // Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    // Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});
