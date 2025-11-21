<?php

use App\Http\Controllers\Api\v1\CompleteTaskController;
use App\Http\Controllers\Api\v1\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::patch('tasks/{task}/complete', CompleteTaskController::class);
    Route::apiResource('tasks', TaskController::class);
});


