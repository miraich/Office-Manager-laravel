<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentaryController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Middleware\EnsureEmailVerified;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:sanctum", EnsureEmailVerified::class])->group(function () {
    Route::get('/user', [UserController::class, "currentUser"]);

    Route::get('/groups/create/info', [GroupController::class, "getCreateGroupInfo"]);

    Route::get('/projects', [ProjectController::class, "index"]);
    Route::get('/project/{project}', [ProjectController::class, "show"]);
    Route::delete('/project/{project}', [ProjectController::class, "destroy"]);
    Route::post('/projects/add', [ProjectController::class, "store"]);
    Route::get('/project/{project}/download', [ProjectController::class, 'download']);
    Route::post('/project/{project}/add/task', [TaskController::class, 'store']);
    Route::patch('/project/{project}/task/{task}', [TaskController::class, 'update']);

    Route::post('/groups/confirm/', [GroupController::class, "confirmUser"]);
    Route::get('/groups', [GroupController::class, "index"]);
    Route::get('/groups/{group}', [GroupController::class, "show"]);
    Route::post('/groups/add', [GroupController::class, "store"]);
    Route::post('/groups/invite', [GroupController::class, "invite"]);
    Route::delete('/groups/delete/{group}', [GroupController::class, "destroy"]);
    Route::delete('/group/{group}/user/{user}', [GroupController::class, "deleteUserFromGroup"]);

    Route::get('/messages', [ChatController::class, 'show']);
    Route::post('/message', [MessageController::class, 'store']);
    Route::post('/comment/{task}', [CommentaryController::class, "store"]);
    Route::post('/logout', [AuthController::class, "logout"]);
    Route::post('/order/subscription', [SubscriptionController::class, 'orderSubscription']);
});

Route::middleware("auth:sanctum")->group(function () {
    Route::post('/email/verify', [VerifyEmailController::class, 'verifyEmail']);
});

Route::post('/login', [AuthController::class, "login"]);
Route::post('/register', [AuthController::class, "register"]);
Route::get('/subscriptions', [SubscriptionController::class, 'index']);


