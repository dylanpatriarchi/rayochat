<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SiteOwner\SiteOwnerDashboardController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [OtpController::class, 'showLoginForm'])->name('login');
Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::get('/verify-otp', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.verify.submit');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Site Owners management
        Route::get('/site-owners', [AdminDashboardController::class, 'siteOwnersIndex'])->name('site-owners.index');
        Route::get('/site-owners/create', [AdminDashboardController::class, 'siteOwnersCreate'])->name('site-owners.create');
        Route::post('/site-owners', [AdminDashboardController::class, 'siteOwnersStore'])->name('site-owners.store');
        Route::get('/site-owners/{siteOwner}', [AdminDashboardController::class, 'siteOwnersShow'])->name('site-owners.show');
        Route::get('/site-owners/{siteOwner}/edit', [AdminDashboardController::class, 'siteOwnersEdit'])->name('site-owners.edit');
        Route::put('/site-owners/{siteOwner}', [AdminDashboardController::class, 'siteOwnersUpdate'])->name('site-owners.update');
        Route::delete('/site-owners/{siteOwner}', [AdminDashboardController::class, 'siteOwnersDestroy'])->name('site-owners.destroy');
        
        // Sites management
        Route::get('/sites', [AdminDashboardController::class, 'sitesIndex'])->name('sites.index');
        Route::get('/sites/{site}', [AdminDashboardController::class, 'sitesShow'])->name('sites.show');
        Route::get('/sites/{site}/edit', [AdminDashboardController::class, 'sitesEdit'])->name('sites.edit');
        Route::put('/sites/{site}', [AdminDashboardController::class, 'sitesUpdate'])->name('sites.update');
        Route::delete('/sites/{site}', [AdminDashboardController::class, 'sitesDestroy'])->name('sites.destroy');
    });
    
    // Site Owner routes
    Route::prefix('site-owner')->name('site-owner.')->middleware('role:site-owner')->group(function () {
        Route::get('/dashboard', [SiteOwnerDashboardController::class, 'index'])->name('dashboard');
        
        // Sites management
        Route::get('/sites/create', [SiteOwnerDashboardController::class, 'create'])->name('sites.create');
        Route::post('/sites', [SiteOwnerDashboardController::class, 'store'])->name('sites.store');
        Route::get('/sites/{site}', [SiteOwnerDashboardController::class, 'show'])->name('sites.show');
        Route::get('/sites/{site}/edit', [SiteOwnerDashboardController::class, 'edit'])->name('sites.edit');
        Route::put('/sites/{site}', [SiteOwnerDashboardController::class, 'update'])->name('sites.update');
        Route::delete('/sites/{site}', [SiteOwnerDashboardController::class, 'destroy'])->name('sites.destroy');
    });
});
