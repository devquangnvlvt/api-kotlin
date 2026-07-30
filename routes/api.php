<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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

    Route::get('/user/settings', [UserSettingController::class, 'show']);
    Route::put('/user/settings', [UserSettingController::class, 'update']);

    // Wallet
    Route::get('/wallet', [WalletController::class, 'show']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);

    // Posts
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike']);
    Route::post('/posts/{post}/save', [PostController::class, 'toggleSave']);
    Route::get('/users/{userId}/posts', [PostController::class, 'userPosts']);

    // Comments
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::get('/comments/{comment}/replies', [CommentController::class, 'replies']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/comments/{comment}/like', [CommentController::class, 'toggleLike']);
});
