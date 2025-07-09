<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::post('/send-code', [AuthController::class, 'sendCode'])->name('otp.send');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        Route::get('/admin/logs', [DashboardController::class, 'logs'])->name('admin.logs');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::post('/admin/settings', [DashboardController::class, 'updateSettings'])->name('admin.settings.update');
