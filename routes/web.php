<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;

/*
|-----------------------------------------------------------------------
| Smart Leave Management System - Routes
|-----------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// -------------------------------------------------------------------------
// Authentication Routes (Public)
// -------------------------------------------------------------------------
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'webLogin'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'webRegister'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------------------------------------------------------------
// Protected Routes (Require Authentication)
// -------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // -------------------------------------------------------------------------
    // Employee Routes
    // -------------------------------------------------------------------------
    // Dashboard
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');

    // Leave Requests
    Route::prefix('leave')->group(function () {
        Route::get('/create', [EmployeeController::class, 'createLeave'])->name('leave.create');
        Route::post('/', [EmployeeController::class, 'storeLeave'])->name('leave.store');
        Route::get('/my-requests', [EmployeeController::class, 'myRequests'])->name('leave.my-requests');
        Route::get('/{id}', [EmployeeController::class, 'showLeave'])->name('leave.show');
        Route::delete('/{id}', [EmployeeController::class, 'cancelLeave'])->name('leave.cancel');
    });

    // Profile
    Route::get('/profile', [EmployeeController::class, 'profile'])->name('profile');
    Route::put('/profile', [EmployeeController::class, 'updateProfile'])->name('profile.update');

    // -------------------------------------------------------------------------
    // Manager Routes
    // -------------------------------------------------------------------------
    Route::prefix('manager')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');

        // Pending Requests
        Route::get('/pending', [ManagerController::class, 'pendingRequests'])->name('manager.pending');

        // Leave Management
        Route::prefix('leave')->group(function () {
            Route::get('/{id}', [ManagerController::class, 'showRequest'])->name('manager.show.request');
            Route::patch('/{id}', [ManagerController::class, 'processRequest'])->name('manager.process.request');
            Route::post('/bulk', [ManagerController::class, 'bulkAction'])->name('manager.bulk.action');
        });

        // History
        Route::get('/history', [ManagerController::class, 'history'])->name('manager.history');
        Route::get('/history/{id}', [ManagerController::class, 'historyDetail'])->name('manager.history.detail');
        Route::get('/history/export', [ManagerController::class, 'exportHistory'])->name('manager.history.export');
    });
});

// -------------------------------------------------------------------------
// Fallback Route
// -------------------------------------------------------------------------
Route::fallback(function () {
    return redirect()->route('login');
});
