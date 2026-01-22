<?php

use App\Http\Controllers\GeoprofsController;
use App\Http\Controllers\RoosterWeekController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerlofAanvraagController;
use App\Http\Controllers\AuthenticatedController;
use App\Http\Middleware\checkRole;
use Illuminate\Support\Facades\Route;

Route::post('/auth/request', [AuthenticatedController::class, 'login']);

// Alleen toegankelijk met geldig Sanctum-token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/request', [AuthenticatedController::class, 'getAuthenticatedUser']);
    Route::delete('/auth/request', [AuthenticatedController::class, 'logout']);
    Route::resource('user', UserController::class);
    // Verlofaanvraag routes
    Route::put('/verlofaanvraag/{verlofAanvraag}/approve', [VerlofAanvraagController::class, 'approve']);
    Route::prefix('user/{user}')->group(function () {
        Route::apiResource('verlofaanvraag', VerlofAanvraagController::class);
        Route::put('/verlofaanvraag/{verlofAanvraag}/reject', [VerlofAanvraagController::class, 'reject']);
        Route::apiResource('rooster_week', RoosterWeekController::class);
        Route::get('/notifications', [UserController::class, 'notifications']);
    });

    Route::apiResource('user', UserController::class)->middleware(checkRole::class);
    Route::get('/geoprofs/aanwezigen', [GeoprofsController::class, 'aanwezigen']);
    Route::get('/geoprofs/afwezigen', [GeoprofsController::class, 'afwezigen']);

});

