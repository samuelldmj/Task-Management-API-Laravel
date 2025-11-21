<?php

use App\Http\Controllers\Api\V2\CompleteTaskController;
use App\Http\Controllers\Api\V2\SummaryController;
use App\Http\Controllers\Api\V2\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v2')->group(function () {

    Route::patch('tasks/{task}/complete', CompleteTaskController::class);
    Route::get('summaries', SummaryController::class);
    Route::apiResource('tasks', TaskController::class);

});
