<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthenticatedController;

Route::delete('/auth/request', AuthenticatedController::class.'@destroy');
Route::get('/auth/request', AuthenticatedController::class.'@getAuthenticatedUser');
