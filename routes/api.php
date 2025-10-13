<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;

Route::prefix('ai')->group(function () {
    Route::post('/chat', [AIController::class, 'chat']);
    Route::get('/status', [AIController::class, 'status']);
    Route::get('/test-external-api', [AIController::class, 'testExternalApi']);
    Route::post('/clear-cache', [AIController::class, 'clearApiCache']);
});


