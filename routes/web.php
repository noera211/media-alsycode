<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PblController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('landing'))->name('landing');

/*
|--------------------------------------------------------------------------
| Guest
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Guru + Siswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru,siswa'])->group(function () {

    /*
    | Dashboard
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    | Materi
    */
    Route::prefix('materi')->name('materi.')->group(function () {

        Route::get('/', [MateriController::class, 'index'])
            ->name('index');

        Route::post('/', [MateriController::class, 'store'])
            ->name('store');

        Route::get('/{materi}', [MateriController::class, 'show'])
            ->name('show');

        Route::put('/{materi}', [MateriController::class, 'update'])
            ->name('update');

        Route::delete('/{materi}', [MateriController::class, 'destroy'])
            ->name('destroy');

        Route::post('/{materi}/status', [MateriController::class, 'updateStatus'])
            ->name('status');
    });

    /*
    | Aktivitas PBL
    | IMPORTANT:
    | Route static harus di atas route parameter /{pblActivity}
    */
    Route::prefix('aktivitas-pbl')->name('pbl.')->group(function () {

        Route::get('/', [PblController::class, 'index'])
            ->name('index');

        // Download file submission
        Route::get('/submission/{submission}/download', [PblController::class, 'downloadSubmission'])
            ->name('submission.download');

        // Guru nilai submission
        Route::post('/submission/{submission}/grade', [PblController::class, 'grade'])
            ->name('grade');

        // Setting level
        Route::post('/level-settings', [PblController::class, 'updateLevelSettings'])
            ->name('level-settings');

        // Create activity
        Route::post('/', [PblController::class, 'store'])
            ->name('store');

        // Submit jawaban siswa
        Route::post('/{pblActivity}/submit', [PblController::class, 'submit'])
            ->name('submit');

        // Edit jawaban siswa
        Route::put('/{pblActivity}/submit/{submission}', [PblController::class, 'updateSubmit'])
            ->name('submit.update');

        // Update activity
        Route::put('/{pblActivity}', [PblController::class, 'update'])
            ->name('update');

        // Delete activity
        Route::delete('/{pblActivity}', [PblController::class, 'destroy'])
            ->name('destroy');

        // Detail activity (taruh terakhir)
        Route::get('/{pblActivity}', [PblController::class, 'show'])
            ->name('show');
    });

    /*
    | Nilai
    */
    Route::prefix('nilai')->name('nilai.')->group(function () {

        Route::get('/', [NilaiController::class, 'index'])
            ->name('index');

        Route::post('/submission/{submission}', [NilaiController::class, 'updateNilai'])
            ->name('update');

        Route::post('/test', [NilaiController::class, 'submitTest'])
            ->name('test.submit');

        Route::post('/questions', [NilaiController::class, 'storeQuestion'])
            ->name('question.store');

        Route::put('/questions/{question}', [NilaiController::class, 'updateQuestion'])
            ->name('question.update');

        Route::delete('/questions/{question}', [NilaiController::class, 'destroyQuestion'])
            ->name('question.destroy');
    });

    /*
    | Compiler
    */
    Route::get('/compiler', fn () => view('compiler.index'))
        ->name('compiler');
});

/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/users', [AdminController::class, 'users'])
            ->name('users');

        Route::post('/users', [AdminController::class, 'storeUser'])
            ->name('users.store');

        Route::put('/users/{user}', [AdminController::class, 'updateUser'])
            ->name('users.update');

        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleActive'])
            ->name('users.toggle');

        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])
            ->name('users.reset-password');
    });