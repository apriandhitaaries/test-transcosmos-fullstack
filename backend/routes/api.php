<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AttachmentController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::apiResource('tasks', TaskController::class);
});

Route::post('/tasks/{task}/attachments', [AttachmentController::class, 'store']);
Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download']);
Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy']);
