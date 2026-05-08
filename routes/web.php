<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PblController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('landing'))->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:guru,siswa'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('materi')->name('materi.')->group(function () {
        Route::get('/', [MateriController::class, 'index'])->name('index');
        Route::post('/', [MateriController::class, 'store'])->name('store');
        Route::get('/{materi}', [MateriController::class, 'show'])->name('show');
        Route::put('/{materi}', [MateriController::class, 'update'])->name('update');
        Route::delete('/{materi}', [MateriController::class, 'destroy'])->name('destroy');
        Route::post('/{materi}/status', [MateriController::class, 'updateStatus'])->name('status');
    });

    Route::prefix('aktivitas-pbl')->name('pbl.')->group(function () {
        Route::get('/', [PblController::class, 'index'])->name('index');
        Route::get('/submission/{submission}/download', [PblController::class, 'downloadSubmission'])->name('submission.download');
        Route::get('/submission/{submission}/view', [PblController::class, 'viewSubmission'])->name('submission.view');
        Route::post('/submission/{submission}/grade', [PblController::class, 'grade'])->name('grade');
        Route::post('/level-settings', [PblController::class, 'updateLevelSettings'])->name('level-settings');
        Route::post('/', [PblController::class, 'store'])->name('store');
        Route::post('/{pblActivity}/submit', [PblController::class, 'submit'])->name('submit');
        Route::put('/{pblActivity}/submit/{submission}', [PblController::class, 'updateSubmit'])->name('submit.update');
        Route::put('/{pblActivity}', [PblController::class, 'update'])->name('update');
        Route::delete('/{pblActivity}', [PblController::class, 'destroy'])->name('destroy');
        Route::get('/{pblActivity}', [PblController::class, 'show'])->name('show');
    });

    Route::prefix('nilai')->name('nilai.')->group(function () {
        Route::get('/', [NilaiController::class, 'index'])->name('index');
        Route::post('/pbl/{siswa}', [NilaiController::class, 'updateNilaiPbl'])->name('pbl.update');
        Route::post('/toggle-test/{siswa}', [NilaiController::class, 'toggleTest'])->name('toggle.test');
        Route::post('/evaluation-set', [NilaiController::class, 'updateEvaluationSet'])->name('evaluation-set.update');
        Route::post('/test', [NilaiController::class, 'submitTest'])->name('test.submit');
        Route::get('/export', [NilaiController::class, 'exportNilai'])->name('export');
    });

    Route::prefix('bank-soal')->name('bank-soal.')->group(function () {
        Route::get('/', [BankSoalController::class, 'index'])->name('index');
        Route::post('/', [BankSoalController::class, 'store'])->name('store');
        Route::put('/{question}', [BankSoalController::class, 'update'])->name('update');
        Route::delete('/{question}', [BankSoalController::class, 'destroy'])->name('destroy');
    });

    Route::get('/compiler', fn () => view('compiler.index'))->name('compiler');

    Route::get('/subject-info/edit', [\App\Http\Controllers\SubjectInfoController::class, 'edit'])->name('subject-info.edit');
    Route::put('/subject-info', [\App\Http\Controllers\SubjectInfoController::class, 'update'])->name('subject-info.update');

    Route::prefix('kumpulan-soal')->name('kumpulan-soal.')->group(function () {
        // Guru
        Route::get('/guru', [\App\Http\Controllers\QuestionSetController::class, 'index'])->name('index');
        Route::get('/guru/buat', [\App\Http\Controllers\QuestionSetController::class, 'create'])->name('create');
        Route::post('/guru', [\App\Http\Controllers\QuestionSetController::class, 'store'])->name('store');
        Route::get('/guru/{kumpulanSoal}/edit', [\App\Http\Controllers\QuestionSetController::class, 'edit'])->name('edit');
        Route::put('/guru/{kumpulanSoal}', [\App\Http\Controllers\QuestionSetController::class, 'update'])->name('update');
        Route::delete('/guru/{kumpulanSoal}', [\App\Http\Controllers\QuestionSetController::class, 'destroy'])->name('destroy');
        Route::get('/guru/{kumpulanSoal}', [\App\Http\Controllers\QuestionSetController::class, 'show'])->name('show');
        Route::post('/guru/{siswa}/{set}/unlock', [\App\Http\Controllers\QuestionSetController::class, 'unlockSetAttempt'])->name('unlock-attempt');

        // Siswa
        Route::get('/', [\App\Http\Controllers\QuestionSetController::class, 'riwayatSiswa'])->name('siswa');
        Route::get('/hasil/{result}', [\App\Http\Controllers\QuestionSetController::class, 'result'])->name('result');
        Route::get('/{kumpulanSoal}/kerjakan', [\App\Http\Controllers\QuestionSetController::class, 'take'])->name('take');
        Route::post('/{kumpulanSoal}/submit', [\App\Http\Controllers\QuestionSetController::class, 'submit'])->name('submit');
    });
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleActive'])->name('users.toggle');
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    });