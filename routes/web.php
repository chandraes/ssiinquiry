<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\SubModulController;
use App\Http\Controllers\ReflectionQuestionController;
use App\Http\Controllers\LearningMaterialController;
use App\Http\Controllers\PracticumUploadSlotController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ForumTeamController;
use App\Http\Controllers\StudentController;

// Form register (GET)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Proses register (POST)
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/', function () {
    return view('index'); // Asumsi nama view Anda
})->name('landing-page');

// ... route Anda yang lain ...

// 👇 [TAMBAHKAN KODE INI] Route untuk menangani pengalihan bahasa
Route::get('lang/{locale}', [LocalizationController::class, 'switch'])->name('language.switch');

Auth::routes();


Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/my-class/{kelas}', [StudentController::class, 'showClass'])->name('student.class.show');
        Route::get('/my-class/{kelas}/sub-module/{subModule}', [StudentController::class, 'showSubModule'])->name('student.submodule.show');
        Route::post('/my-class/{kelas}/sub-module/{subModule}/complete', [StudentController::class, 'markAsComplete'])->name('student.submodule.complete');
        Route::post('/my-class/{kelas}/sub-module/{subModule}/reflection', [StudentController::class, 'storeReflection'])->name('student.reflection.store');
        Route::post('/my-class/{kelas}/sub-module/{subModule}/practicum/{slot}', [StudentController::class, 'storePracticum'])->name('student.practicum.store');
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    });

    Route::prefix('profil')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
        Route::get('/ubah', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/delete-foto', [App\Http\Controllers\ProfileController::class, 'delete_foto'])->name('profile.delete-foto');
    });

    Route::group(['prefix' => 'pengguna', 'middleware' => ['role:admin,guru']], function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'show'])->name('user');
        Route::get('/tambah', [App\Http\Controllers\UserController::class, 'create'])->name('user.create');
        Route::patch('/ubah/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
        Route::post('/store', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
        Route::post('/upload', [App\Http\Controllers\UserController::class, 'upload'])->name('user.upload');
        Route::delete('/delete/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.delete');
    });

    Route::group(['prefix' => 'modul', 'middleware' => ['role:admin,guru']], function () {
        Route::get('/', [App\Http\Controllers\ModulController::class, 'index'])->name('modul');
        Route::get('/tambah', [App\Http\Controllers\ModulController::class, 'create'])->name('modul.create');
        Route::patch('/ubah/{id}', [App\Http\Controllers\ModulController::class, 'update'])->name('modul.update');
        Route::post('/store', [App\Http\Controllers\ModulController::class, 'store'])->name('modul.store');
        // Route::post('/upload', [App\Http\Controllers\ModulController::class, 'upload'])->name('modul.upload');
        Route::delete('/delete/{id}', [App\Http\Controllers\ModulController::class, 'destroy'])->name('modul.delete');
        Route::get('/search', [App\Http\Controllers\ModulController::class, 'search'])->name('search-guru');
        Route::get('/search-phyphox', [App\Http\Controllers\ModulController::class, 'search_phyphox'])->name('search-phyphox');

        Route::get('/{modul}', [ModulController::class, 'show'])->name('modul.show');

    });

    Route::group(['prefix' => 'learning-material', 'middleware' => ['role:admin,guru']], function () {

        Route::post('/', [LearningMaterialController::class, 'store'])->name('learning_material.store');
        Route::get('/{material}/edit', [LearningMaterialController::class, 'edit'])->name('learning_material.edit.json');
        Route::put('/{material}', [LearningMaterialController::class, 'update'])->name('learning_material.update');
        Route::delete('/{material}', [LearningMaterialController::class, 'destroy'])->name('learning_material.destroy');
    });

    Route::group(['prefix' => 'reflection-question', 'middleware' => ['role:admin,guru']], function () {
        Route::post('/', [ReflectionQuestionController::class, 'store'])->name('reflection_question.store');
        Route::get('/{question}/edit', [ReflectionQuestionController::class, 'edit'])->name('reflection_question.edit.json');
        Route::put('/{question}', [ReflectionQuestionController::class, 'update'])->name('reflection_question.update');
        Route::delete('/{question}', [ReflectionQuestionController::class, 'destroy'])->name('reflection_question.destroy');
    });

    Route::group(['prefix' => 'submodul', 'middleware' => ['role:admin,guru']], function () {
        Route::post('/', [SubModulController::class, 'store'])->name('submodul.store');
        Route::get('/show/{subModul}', [SubModulController::class, 'show'])->name('submodul.show');
        Route::get('/{subModul}/json', [SubModulController::class, 'showJson'])->name('submodul.show.json');
        Route::put('/{subModul}', [SubModulController::class, 'update'])->name('submodul.update');
        Route::delete('/{subModul}', [SubModulController::class, 'destroy'])->name('submodul.destroy');
    });

    Route::group(['prefix' => 'practicum-slot', 'middleware' => ['role:admin,guru']], function () {
        Route::post('/', [PracticumUploadSlotController::class, 'store'])->name('practicum_slot.store');
        Route::get('/{slot}/edit', [PracticumUploadSlotController::class, 'edit'])->name('practicum_slot.edit.json');
        Route::put('/{slot}', [PracticumUploadSlotController::class, 'update'])->name('practicum_slot.update');
        Route::delete('/{slot}', [PracticumUploadSlotController::class, 'destroy'])->name('practicum_slot.destroy');
    });

    Route::group(['prefix' => 'kelas', 'middleware' => ['role:admin,guru']], function () {
        Route::get('/', [App\Http\Controllers\KelasController::class, 'index'])->name('kelas');
        Route::get('/tambah', [App\Http\Controllers\KelasController::class, 'create'])->name('kelas.create');
        Route::patch('/ubah/{id}', [App\Http\Controllers\KelasController::class, 'update'])->name('kelas.update');
        Route::post('/store', [App\Http\Controllers\KelasController::class, 'store'])->name('kelas.store');
        // Route::post('/upload', [App\Http\Controllers\ModulController::class, 'upload'])->name('modul.upload');
        Route::delete('/delete/{id}', [App\Http\Controllers\KelasController::class, 'destroy'])->name('kelas.delete');
        Route::get('/guru/search', [App\Http\Controllers\KelasController::class, 'search_guru_pengajar'])->name('search-pengajar');

        Route::get('/{kelas}/forums', [KelasController::class, 'showForums'])->name('kelas.forums');
        Route::get('/{kelas}', [KelasController::class, 'show'])->name('kelas.show');

        // [BARU] Halaman "manajemen tim" untuk 1 forum spesifik di 1 kelas spesifik
        Route::get('/{kelas}/forum/{subModule}/teams', [ForumTeamController::class, 'index'])->name('kelas.forum.teams');

        Route::group(['prefix' => 'peserta', 'middleware' => ['role:admin,guru']], function () {
            Route::get('/{id}', [App\Http\Controllers\KelasUserController::class, 'index'])->name('kelas.peserta');
            Route::get('/tambah', [App\Http\Controllers\KelasUserController::class, 'create'])->name('kelas.peserta.create');
            Route::post('/store', [App\Http\Controllers\KelasUserController::class, 'store'])->name('kelas.peserta.store');
            Route::post('/upload', [App\Http\Controllers\KelasUserController::class, 'upload'])->name('kelas.peserta.upload');
            Route::post('/pro/{id}', [App\Http\Controllers\KelasUserController::class, 'markPro'])->name('kelas.peserta.pro');
            Route::post('/kontra/{id}', [App\Http\Controllers\KelasUserController::class, 'markKontra'])->name('kelas.peserta.kontra');
            Route::delete('/delete/{id}', [App\Http\Controllers\KelasUserController::class, 'destroy'])->name('kelas.peserta.delete');
        });
    });

      Route::group(['prefix' => 'forum-teams', 'middleware' => ['role:admin,guru']], function () {
        Route::post('/assign', [ForumTeamController::class, 'assignTeam'])->name('forum.teams.assign');
        Route::post('/remove', [ForumTeamController::class, 'removeTeam'])->name('forum.teams.remove');
      });

    Route::get('/download-template', [App\Http\Controllers\KelasUserController::class, 'downloadTemplate'])->name('kelas.peserta.download-template');

    Route::prefix('siswa')->middleware(['role:siswa'])->group(function () {
        // =====================
        // Route KELAS
        // =====================
        Route::prefix('kelas')->group(function () {
            Route::get('/{id}', [App\Http\Controllers\KelasController::class, 'siswa_kelas'])
                ->name('siswa.kelas');
            Route::post('/{kelas_id}/join', [App\Http\Controllers\KelasController::class, 'siswa_join'])
                ->name('siswa.kelas.join');
            Route::post('/jawaban/{id}/simpan', [App\Http\Controllers\KelasController::class, 'simpan_jawaban'])
                ->name('kelas.jawaban.simpan');
        });

        // =====================
        // Route MODUL
        // =====================
        Route::prefix('modul')->group(function () {
            Route::get('/{modul}', [App\Http\Controllers\ModulController::class, 'show_siswa'])
                ->name('siswa.modul.show');
        });

        // =====================
        // Route SUBMODUL & REFLEKSI
        // =====================
        Route::prefix('submodul')->group(function () {
            Route::get('/show/{subModul}', [App\Http\Controllers\SubModulController::class, 'show_siswa'])
                ->name('siswa.submodul.show');
            Route::post('/reflection/store', [App\Http\Controllers\ReflectionQuestionController::class, 'storeAnswer'])
                ->name('siswa.reflection.store');
            Route::post('/practicum-slot/{id}/upload-csv', [App\Http\Controllers\PracticumSlotController::class, 'uploadCsv'])
                ->name('practicum_slot.upload_csv');

        });
    });



});
