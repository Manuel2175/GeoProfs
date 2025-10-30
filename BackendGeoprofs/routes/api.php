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
        Route::put('verlofaanvraag/{verlofaanvraag}/reject', [VerlofAanvraagController::class, 'reject'])
            ->name('user.verlofaanvraag.reject');
        Route::put('verlofaanvraag/{verlofaanvraag}/approve', [VerlofAanvraagController::class, 'approve'])
            ->name('user.verlofaanvraag.approve');
    });
});

