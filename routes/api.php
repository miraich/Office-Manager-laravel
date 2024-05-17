<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentaryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:sanctum")->group(function () {
    Route::get('/user', [UserController::class, "show"]);
    Route::get('/messages', [ChatController::class, 'show']);
    Route::post('/message', [MessageController::class, 'store']);
    Route::post('/task', [TaskController::class, "store"]);
    Route::post('/comment/{task}', [CommentaryController::class, "store"]);
    Route::post('/logout', [AuthController::class, "logout"])->name('logout');
    Route::post('/email/verify', [VerifyEmailController::class, 'verifyEmail']);
});

Route::post('/login', [AuthController::class, "login"])->name('login');
Route::post('/register', [AuthController::class, "register"]);

Route::get('/test', function (Request $request) {
    echo 'adasdsad';
});

