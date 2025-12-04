<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VerlofAanvraagController;
use App\Http\Controllers\AuthenticatedController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/request', [AuthenticatedController::class, 'login']);

// Alleen toegankelijk met geldig Sanctum-token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/request', [AuthenticatedController::class, 'getAuthenticatedUser']);
    Route::delete('/auth/request', [AuthenticatedController::class, 'logout']);
    Route::resource('user', UserController::class);
    // Verlofaanvraag routes
    Route::prefix('user/{user}')->group(function () {
        Route::apiResource('verlofaanvraag', VerlofAanvraagController::class);
        Route::put('/verlofaanvraag/{verlofAanvraag}/reject', [VerlofAanvraagController::class, 'reject']);
        Route::put('/verlofaanvraag/{verlofAanvraag}/approve', [VerlofAanvraagController::class, 'approve']);
    });

    Route::apiResource('user', UserController::class)->middleware(\App\Http\Middleware\checkRole::class);


});

