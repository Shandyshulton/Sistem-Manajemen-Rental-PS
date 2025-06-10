<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ConsoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Halaman utama redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ROUTE GUEST
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password');
    Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('forgot.password.post');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password');
    Route::post('/reset-password', [AuthController::class, 'processResetPassword'])->name('reset.password.post');
});

// ROUTE AUTH (setelah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard', ['staff' => Auth::user()]);
    })->name('dashboard');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    })->name('logout');

    Route::resource('bookings', BookingController::class);
    Route::resource('consoles', ConsoleController::class);
    Route::resource('staffs', StaffController::class);
});

// API tambahan
Route::get('/transaction-stats', [DashboardController::class, 'getTransactionStats']);
