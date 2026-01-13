<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VerlofAanvraagController;
use App\Http\Controllers\AuthenticatedController;
use App\Http\Middleware\checkManager;
use \App\Http\Middleware\checkHR;
use Illuminate\Support\Facades\Route;

Route::post('/auth/request', [AuthenticatedController::class, 'login']);

// Alleen toegankelijk met geldig Sanctum-token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/request', [AuthenticatedController::class, 'getAuthenticatedUser']);
    Route::delete('/auth/request', [AuthenticatedController::class, 'logout']);
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/{user}', [UserController::class, 'show']);
    Route::put('/user/{user}', [UserController::class, 'update'])->middleware(\App\Http\Middleware\checkRole::class);

        Route::prefix('user/{user}')->group(function () {
            Route::apiResource('verlofaanvraag', VerlofAanvraagController::class);
            Route::put('/verlofaanvraag/{verlofAanvraag}/reject', [VerlofAanvraagController::class, 'reject'])->middleware(checkManager::class);
            Route::put('/verlofaanvraag/{verlofAanvraag}/approve', [VerlofAanvraagController::class, 'approve'])->middleware(checkManager::class);
        });
});

