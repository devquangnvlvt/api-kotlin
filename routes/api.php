<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Google Auth
Route::post('/auth/google/token', [AuthController::class, 'handleGoogleToken']);

// Protected routes - require authentication
Route::middleware('auth:sanctum')->group(function () {


    Route::post('/auth/logout', [AuthController::class, 'logout']);



    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
});
