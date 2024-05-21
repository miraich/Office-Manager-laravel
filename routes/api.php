<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentaryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Middleware\EnsureEmailVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:sanctum")->group(function () {
    Route::get('/user', [UserController::class, "show"])->middleware(EnsureEmailVerified::class);
    Route::get('/messages', [ChatController::class, 'show']);
    Route::post('/message', [MessageController::class, 'store']);
    Route::post('/task', [TaskController::class, "store"]);
    Route::post('/comment/{task}', [CommentaryController::class, "store"]);
    Route::post('/logout', [AuthController::class, "logout"]);
    Route::post('/email/verify', [VerifyEmailController::class, 'verifyEmail']);
    Route::post('/order/subscription', [SubscriptionController::class, 'orderSubscription']);

});

Route::post('/login', [AuthController::class, "login"]);
Route::post('/register', [AuthController::class, "register"]);
Route::get('/subscriptions', [SubscriptionController::class, 'index']);


