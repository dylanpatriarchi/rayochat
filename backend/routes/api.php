<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WidgetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Routes for widget communication
*/

Route::prefix('widget')->group(function () {
    Route::post('/chat', [WidgetController::class, 'chat']);
    Route::post('/rate', [WidgetController::class, 'rate']);
});
