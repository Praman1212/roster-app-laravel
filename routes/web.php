<?php
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Redirect home to tasks
Route::get('/', function () {
    return response()->json(['message' => 'FamRoster API is running!']);
});
// All routes require login
Route::middleware('auth')->group(function () {

    // Household setup
    Route::get('/household/setup',  [HouseholdController::class, 'index']);
    Route::post('/household/create',[HouseholdController::class, 'create']);
    Route::post('/household/join',  [HouseholdController::class, 'join']);

    // Tasks
    Route::get('/tasks',                    [TaskController::class, 'index']);
    Route::get('/tasks/create',             [TaskController::class, 'create']);
    Route::post('/tasks',                   [TaskController::class, 'store']);
    Route::get('/tasks/{task}/edit',        [TaskController::class, 'edit']);
    Route::put('/tasks/{task}',             [TaskController::class, 'update']);
    Route::delete('/tasks/{task}',          [TaskController::class, 'destroy']);
    Route::post('/tasks/{task}/status',     [TaskController::class, 'updateStatus']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
});

