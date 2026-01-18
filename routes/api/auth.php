<?php

use App\Presentation\API\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

// Public auth routes
Route::post('register', [AuthController::class, 'register'])->middleware('throttle:6,1');
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:6,1');

// Protected auth routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('tokens', [AuthController::class, 'tokens']);
    Route::delete('tokens/{tokenId}', [AuthController::class, 'revokeToken']);
});
