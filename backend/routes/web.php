<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiteOwnerController;
use App\Http\Middleware\AuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('auth.login');
});

// Authentication routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send-otp');
    Route::get('/verify', [AuthController::class, 'showVerify'])->name('verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware([AuthMiddleware::class . ':admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Site Owner management
    Route::get('/site-owners', [AdminController::class, 'siteOwners'])->name('site-owners');
    Route::get('/site-owners/create', [AdminController::class, 'createSiteOwner'])->name('create-site-owner');
    Route::post('/site-owners', [AdminController::class, 'storeSiteOwner'])->name('store-site-owner');
    Route::get('/site-owners/{id}/analytics', [AdminController::class, 'siteOwnerAnalytics'])->name('site-owner-analytics');
    Route::post('/site-owners/{id}/deactivate', [AdminController::class, 'deactivateSiteOwner'])->name('deactivate-site-owner');
    Route::post('/site-owners/{id}/activate', [AdminController::class, 'activateSiteOwner'])->name('activate-site-owner');
    
    // Change requests
    Route::get('/companies/{id}/change-request', [AdminController::class, 'createChangeRequest'])->name('create-change-request');
    Route::post('/companies/{id}/change-request', [AdminController::class, 'storeChangeRequest'])->name('store-change-request');
});

// Site Owner routes
Route::prefix('site-owner')->name('site-owner.')->middleware([AuthMiddleware::class . ':site_owner'])->group(function () {
    Route::get('/dashboard', [SiteOwnerController::class, 'dashboard'])->name('dashboard');
    
    // Company info
    Route::get('/company-info', [SiteOwnerController::class, 'companyInfo'])->name('company-info');
    Route::post('/company-info', [SiteOwnerController::class, 'updateCompanyInfo'])->name('update-company-info');
    
    // Documents
    Route::get('/documents', [SiteOwnerController::class, 'documents'])->name('documents');
    Route::post('/documents/upload', [SiteOwnerController::class, 'uploadDocument'])->name('upload-document');
    Route::delete('/documents/{id}', [SiteOwnerController::class, 'deleteDocument'])->name('delete-document');
    
    // Analytics
    Route::get('/analytics', [SiteOwnerController::class, 'analytics'])->name('analytics');
    
    // Change requests
    Route::get('/change-requests', [SiteOwnerController::class, 'changeRequests'])->name('change-requests');
    Route::post('/change-requests/{id}/approve', [SiteOwnerController::class, 'approveChangeRequest'])->name('approve-change-request');
    Route::post('/change-requests/{id}/reject', [SiteOwnerController::class, 'rejectChangeRequest'])->name('reject-change-request');
    
    // API Key
    Route::get('/api-key', [SiteOwnerController::class, 'showApiKey'])->name('api-key');
    
    // Plugin download
    Route::get('/download-plugin', [SiteOwnerController::class, 'downloadPlugin'])->name('download-plugin');
});
