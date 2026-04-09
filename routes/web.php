<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PblController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────
Route::get('/', fn() => view('landing'))->name('landing');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Authenticated (guru + siswa) ──────────────────────────────────────────
Route::middleware(['auth', 'role:guru,siswa'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Materi
    Route::prefix('materi')->name('materi.')->group(function () {
        Route::get('/',                    [MateriController::class, 'index'])->name('index');
        Route::get('/{materi}',            [MateriController::class, 'show'])->name('show');
        Route::post('/',                   [MateriController::class, 'store'])->name('store');
        Route::put('/{materi}',            [MateriController::class, 'update'])->name('update');
        Route::delete('/{materi}',         [MateriController::class, 'destroy'])->name('destroy');
        Route::post('/{materi}/status',    [MateriController::class, 'updateStatus'])->name('status');
    });

    // PBL Aktivitas
    Route::prefix('aktivitas-pbl')->name('pbl.')->group(function () {
        Route::get('/',                          [PblController::class, 'index'])->name('index');
        Route::get('/{pblActivity}',             [PblController::class, 'show'])->name('show');
        Route::post('/',                         [PblController::class, 'store'])->name('store');
        Route::put('/{pblActivity}',             [PblController::class, 'update'])->name('update');
        Route::delete('/{pblActivity}',          [PblController::class, 'destroy'])->name('destroy');
        Route::post('/{pblActivity}/submit',     [PblController::class, 'submit'])->name('submit');
        Route::post('/submission/{submission}/grade', [PblController::class, 'grade'])->name('grade');
        Route::post('/level-settings',           [PblController::class, 'updateLevelSettings'])->name('level-settings');
    });

    // Nilai & Test
    Route::prefix('nilai')->name('nilai.')->group(function () {
        Route::get('/',                          [NilaiController::class, 'index'])->name('index');
        Route::post('/submission/{submission}',  [NilaiController::class, 'updateNilai'])->name('update');
        Route::post('/test',                     [NilaiController::class, 'submitTest'])->name('test.submit');
        Route::post('/questions',                [NilaiController::class, 'storeQuestion'])->name('question.store');
        Route::put('/questions/{question}',      [NilaiController::class, 'updateQuestion'])->name('question.update');
        Route::delete('/questions/{question}',   [NilaiController::class, 'destroyQuestion'])->name('question.destroy');
    });

    // Compiler
    Route::get('/compiler', fn() => view('compiler.index'))->name('compiler');
});

// ── Admin only ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',       [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',  [AdminController::class, 'users'])->name('users');
    Route::post('/users',              [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}',        [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/toggle',[AdminController::class, 'toggleActive'])->name('users.toggle');
    Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
});
