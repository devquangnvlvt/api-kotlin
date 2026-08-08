<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CheckinLogController;
use App\Http\Controllers\DailyTaskController;
use App\Http\Controllers\FrameController;
use App\Http\Controllers\StickerController;
use App\Http\Controllers\StickerPackController;
use App\Http\Controllers\UserDailyTaskController;
use App\Http\Controllers\UserStickerPackController;

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


    // Follows
    Route::post('/follows', [FollowController::class, 'follow']);
    Route::post('/unfollows', [FollowController::class, 'unfollow']);
    Route::get('/is-following', [FollowController::class, 'isFollowing']);
    Route::get('/followers', [FollowController::class, 'getFollowers']);
    Route::get('/following', [FollowController::class, 'getFollowing']);

    // Badge
    Route::get('/badges', [BadgeController::class, 'index']);
    Route::get('/user/badges', [BadgeController::class, 'getUserBadges']);
    Route::post('/badges/{badgeId}/buy', [BadgeController::class, 'buy']);
    Route::post('/badges/{badgeId}/receive', [BadgeController::class, 'receive']); // nhận huy hiệu thành tích

    // frame 
    Route::get('/frames', [FrameController::class, 'index']);
    Route::get('/user/frames', [FrameController::class, 'getUserFrames']);
    Route::post('/frames/{frameId}/buy', [FrameController::class, 'buy']);
    Route::post('/frames/{frameId}/receive', [FrameController::class, 'receive']); // nhận khung thành tích
    Route::post('/frames/{frameId}/active', [FrameController::class, 'userActiveFrame']); // đeo khung viền

    // Sticker Pack
    Route::get('/sticker-packs', [StickerPackController::class, 'index']);
    Route::post('/sticker-packs/{id}/buy', [StickerPackController::class, 'buy']);
    Route::get('/sticker-packs/{id}', [StickerPackController::class, 'show']);

    // User Sticker Pack
    Route::get('/user/sticker-packs', [UserStickerPackController::class, 'index']);


    // daily task
    Route::get('daily-tasks', [DailyTaskController::class, 'index']);
    Route::post('daily-tasks/{id}/update', [DailyTaskController::class, 'updateStatus']);

    //user daily task
    Route::get('user/daily-tasks', [UserDailyTaskController::class, 'index']);


    // Checkin
    Route::post('/checkin', [CheckinLogController::class, 'checkin']);
    Route::get('/checkin/status', [CheckinLogController::class, 'status']);
});
