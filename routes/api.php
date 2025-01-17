<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;

// Public routes
Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);

// Get authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authenticated routes
Route::middleware('auth:custom')->group(function () {
    // Demo routes (accessible to demo and standard users)
    Route::middleware('demo_or_standard')->group(function () {
        Route::get('/demo/test', [TestController::class, 'demoTest'])->name('demo.test');
    });

    // Standard routes (only accessible to standard users)
    Route::middleware('standard')->group(function () {
        Route::get('/standard/test', [TestController::class, 'standardTest'])->name('standard.test');
    });

    // Shared route for authenticated users
    
});
Route::get('/shared/test', [TestController::class, 'sharedTest'])->name('shared.test');