<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// public — no login needed
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// protected — must be logged in
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout',           [AuthController::class, 'logout']);
    Route::get('/me',                [AuthController::class, 'me']);

    Route::post('/household/create', [HouseholdController::class, 'create']);
    Route::post('/household/join',   [HouseholdController::class, 'join']);

    Route::get('/tasks',             [TaskController::class, 'index']);
    Route::post('/tasks',            [TaskController::class, 'store']);
    Route::put('/tasks/{task}',      [TaskController::class, 'update']);
    Route::delete('/tasks/{task}',   [TaskController::class, 'destroy']);

    Route::get('/notifications',               [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read',   [NotificationController::class, 'markRead']);
    Route::post('/notifications/read-all',     [NotificationController::class, 'markAllRead']);
});