<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

});


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (SANCTUM)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('leave')->group(function () {

        // Employee
        Route::post('/', [LeaveController::class, 'create']);
        Route::get('/my-requests', [LeaveController::class, 'myRequests']);

        // Manager / Admin
        Route::get('/pending', [LeaveController::class, 'pending']);
        Route::patch('/{id}/approve', [LeaveController::class, 'approve']);
        Route::patch('/{id}/reject', [LeaveController::class, 'reject']);
    });

});