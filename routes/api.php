<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EventController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'custom.throttle'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);


    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/all', [EventController::class, 'all']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::delete('/events/{id}/force', [EventController::class, 'forceDelete']); //

    // 🔹 This will return current user if token is valid
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
});
