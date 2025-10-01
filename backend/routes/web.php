<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [OtpController::class, 'showLoginForm'])->name('login');
Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::get('/verify-otp', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.verify.submit');
Route::post('/logout', [OtpController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
