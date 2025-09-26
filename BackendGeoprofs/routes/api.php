<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatedController;

Route::get('/test', function () {
    return response()->json(['ok' => true]);
});

Route::prefix('auth')->group(function () {
    Route::post('/request', [AuthenticatedController::class, 'login']);
    Route::get('/request', [AuthenticatedController::class, 'getAuthenticatedUser']);
    Route::delete('/request', [AuthenticatedController::class, 'destroy']);
});
