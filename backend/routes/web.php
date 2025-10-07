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

// Legal pages (public)
Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('terms-of-service');

Route::get('/cookie-policy', function () {
    return view('legal.cookie-policy');
})->name('cookie-policy');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Admin dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Users management (new unified interface)
        Route::get('/users', [AdminDashboardController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/create', [AdminDashboardController::class, 'usersCreate'])->name('users.create');
        Route::post('/users', [AdminDashboardController::class, 'usersStore'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminDashboardController::class, 'usersEdit'])->name('users.edit');
        Route::put('/users/{user}', [AdminDashboardController::class, 'usersUpdate'])->name('users.update');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'usersDestroy'])->name('users.destroy');
        
        // Admin Profile management
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminDashboardController::class, 'profileUpdate'])->name('profile.update');
        
        // Analytics management
        Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/site/{site}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'site'])->name('analytics.site');
        
        // Site Owners management (legacy routes for existing views)
        Route::get('/site-owners', [AdminDashboardController::class, 'siteOwnersIndex'])->name('site-owners.index');
        Route::get('/site-owners/create', [AdminDashboardController::class, 'siteOwnersCreate'])->name('site-owners.create');
        Route::post('/site-owners', [AdminDashboardController::class, 'siteOwnersStore'])->name('site-owners.store');
        Route::get('/site-owners/{siteOwner}', [AdminDashboardController::class, 'siteOwnersShow'])->name('site-owners.show');
        Route::get('/site-owners/{siteOwner}/edit', [AdminDashboardController::class, 'siteOwnersEdit'])->name('site-owners.edit');
        Route::put('/site-owners/{siteOwner}', [AdminDashboardController::class, 'siteOwnersUpdate'])->name('site-owners.update');
        Route::delete('/site-owners/{siteOwner}', [AdminDashboardController::class, 'siteOwnersDestroy'])->name('site-owners.destroy');
        
        // Sites management (keep existing routes for actions from accordion)
        Route::get('/sites', [AdminDashboardController::class, 'sitesIndex'])->name('sites.index');
        Route::get('/sites/{site}', [AdminDashboardController::class, 'sitesShow'])->name('sites.show');
        Route::get('/sites/{site}/edit', [AdminDashboardController::class, 'sitesEdit'])->name('sites.edit');
        Route::put('/sites/{site}', [AdminDashboardController::class, 'sitesUpdate'])->name('sites.update');
        Route::delete('/sites/{site}', [AdminDashboardController::class, 'sitesDestroy'])->name('sites.destroy');
        
        // Site info management
        Route::get('/sites/{site}/edit-info', [AdminDashboardController::class, 'sitesEditInfo'])->name('sites.edit-info');
        Route::put('/sites/{site}/info', [AdminDashboardController::class, 'sitesUpdateInfo'])->name('sites.update-info');
        
        // Analytics
        Route::get('/analytics', [AdminDashboardController::class, 'analyticsIndex'])->name('analytics.index');
        Route::get('/analytics/site/{site}', [AdminDashboardController::class, 'siteAnalytics'])->name('analytics.site');
    });
    
    // Site Owner routes
    Route::prefix('site-owner')->name('site-owner.')->middleware('role:site-owner')->group(function () {
        Route::get('/dashboard', [SiteOwnerDashboardController::class, 'index'])->name('dashboard');
        
        // Sites management
        Route::get('/sites', [SiteOwnerDashboardController::class, 'sitesIndex'])->name('sites.index');
        Route::get('/sites/create', [SiteOwnerDashboardController::class, 'create'])->name('sites.create');
        Route::post('/sites', [SiteOwnerDashboardController::class, 'store'])->name('sites.store');
        Route::get('/sites/{site}', [SiteOwnerDashboardController::class, 'show'])->name('sites.show');
        Route::get('/sites/{site}/edit', [SiteOwnerDashboardController::class, 'edit'])->name('sites.edit');
        Route::put('/sites/{site}', [SiteOwnerDashboardController::class, 'update'])->name('sites.update');
        Route::delete('/sites/{site}', [SiteOwnerDashboardController::class, 'destroy'])->name('sites.destroy');
        
        // Site info management
        Route::get('/sites/{site}/edit-info', [SiteOwnerDashboardController::class, 'editInfo'])->name('sites.edit-info');
        Route::put('/sites/{site}/info', [SiteOwnerDashboardController::class, 'updateInfo'])->name('sites.update-info');
        
        // Profile management
        Route::get('/profile', [SiteOwnerDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [SiteOwnerDashboardController::class, 'profileUpdate'])->name('profile.update');
        
        // Analytics
        Route::get('/analytics', [SiteOwnerDashboardController::class, 'analyticsIndex'])->name('analytics.index');
        Route::get('/analytics/site/{site}', [SiteOwnerDashboardController::class, 'siteAnalytics'])->name('analytics.site');
        
        // Integrations
        Route::get('/integrations', [SiteOwnerDashboardController::class, 'integrationsIndex'])->name('integrations.index');
        Route::get('/integrations/wordpress/download', [SiteOwnerDashboardController::class, 'downloadWordPressPlugin'])->name('integrations.wordpress.download');
        Route::get('/integrations/shopify/download', [SiteOwnerDashboardController::class, 'downloadShopifyApp'])->name('integrations.shopify.download');
    });
});
