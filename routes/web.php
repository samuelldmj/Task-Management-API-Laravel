<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Up n Running...';
});


Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class)->middleware('guest');
    Route::post('logout', LogoutController::class);
    Route::post('register', RegisterController::class)->middleware('guest');
});


Route::get('/auth/user', function () {
    return response()->json(['user' => auth()->user()]);
})->middleware('auth:sanctum');